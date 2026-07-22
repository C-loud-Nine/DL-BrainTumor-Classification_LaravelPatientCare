"""
Unified FastAPI model server for the Brain-Tumor MRI system.

Serves a POST /predict endpoint that the Laravel app calls (FASTAPI_URL,
FASTAPI_URL_2, FASTAPI_URL_3). Each instance loads:
  * one tumor classifier (4-class: glioma/meningioma/pituitary/notumor)
  * the shared MRI-vs-nonMRI detector (so every response carries `is_mri`)

Configure per-instance via environment variables:
  TUMOR_MODEL : path to the tumor SavedModel directory
  PORT        : port to listen on
See run_servers.py for the 8001/8002/8003 wiring.

Response JSON:
  { "is_mri": bool, "mri_confidence": float,
    "prediction": str, "confidence": float,
    "probabilities": {label: prob, ...} }
"""
import os
import io
import base64
import numpy as np
import cv2
from PIL import Image
import tensorflow as tf
from fastapi import FastAPI, UploadFile, File
from fastapi.responses import JSONResponse

# ---------------------------------------------------------------- config
BRAINT = r"C:\Users\Shafi.09\AnocondaProjects\BrainT\Final training\models"
TUMOR_MODEL = os.environ.get("TUMOR_MODEL", os.path.join(BRAINT, "presys"))
MRI_MODEL = os.environ.get(
    "MRI_MODEL",
    r"C:\Users\Shafi.09\AnocondaProjects\MRI_Image_Classificaion\102mod",
)
TUMOR_LABELS = ["glioma", "meningioma", "pituitary", "notumor"]
# Last conv block of the tumor CNN - the layer Grad-CAM reads activations from.
GRADCAM_LAYER = os.environ.get("GRADCAM_LAYER", "Conv5")

# ---------------------------------------------------------------- load models
print(f"[model_server] loading tumor model: {TUMOR_MODEL}", flush=True)
tumor = tf.keras.models.load_model(TUMOR_MODEL, compile=False)
print(f"[model_server] loading MRI detector: {MRI_MODEL}", flush=True)
mri_detector = tf.keras.models.load_model(MRI_MODEL, compile=False)
print("[model_server] models ready.", flush=True)

app = FastAPI(title="Brain Tumor Model Server")


# ---------------------------------------------------------------- preprocessing
def preprocess_tumor(img_bytes: bytes) -> np.ndarray:
    """Grayscale + CLAHE + median blur + resize(150) + /255 -> (1,150,150,1).
    Matches the training notebook `mri_preprocessing`."""
    arr = np.frombuffer(img_bytes, np.uint8)
    img = cv2.imdecode(arr, cv2.IMREAD_COLOR)          # BGR, 3-channel
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8, 8))
    enhanced = clahe.apply(gray)
    blurred = cv2.medianBlur(enhanced, 3)
    resized = cv2.resize(blurred, (150, 150))
    x = (resized / 255.0).astype("float32")
    return x[np.newaxis, ..., np.newaxis]


def preprocess_mri(img_bytes: bytes) -> np.ndarray:
    """RGB resize(128) + /255 -> (1,128,128,3). Matches the MRI-detector
    notebook (ImageDataGenerator rescale 1./255, target_size 128)."""
    img = Image.open(io.BytesIO(img_bytes)).convert("RGB").resize((128, 128))
    x = (np.asarray(img) / 255.0).astype("float32")
    return x[np.newaxis, ...]


# ---------------------------------------------------------------- grad-cam
# Built once; rebuilding the graph per request is slow.
_grad_model = tf.keras.models.Model(
    inputs=tumor.inputs,
    outputs=[tumor.get_layer(GRADCAM_LAYER).output, tumor.output],
)


def grad_cam(x: np.ndarray, class_idx: int = None, eps: float = 1e-8):
    """Grad-CAM heatmap for input batch `x`. Mirrors the training notebook's
    `generate_grad_cam`. Returns (heatmap HxW in 0..1, class_idx)."""
    with tf.GradientTape() as tape:
        conv_out, preds = _grad_model(x)
        if class_idx is None:
            class_idx = int(tf.argmax(preds[0]))
        loss = preds[:, class_idx]

    grads = tape.gradient(loss, conv_out)
    pooled = tf.reduce_mean(grads, axis=(0, 1, 2))

    heat = tf.cast(conv_out[0], tf.float32) @ tf.cast(pooled, tf.float32)[..., tf.newaxis]
    heat = tf.squeeze(heat)
    heat = tf.maximum(heat, 0) / (tf.math.reduce_max(heat) + eps)
    return heat.numpy(), int(class_idx)


def _png_data_uri(img: np.ndarray) -> str:
    ok, buf = cv2.imencode(".png", img)
    if not ok:
        raise RuntimeError("failed to encode PNG")
    return "data:image/png;base64," + base64.b64encode(buf.tobytes()).decode()


def render_overlay(x: np.ndarray, heat: np.ndarray, size: int = 400):
    """Returns (base, overlay) as base64 PNG data URIs, pixel-aligned at `size`.

    `base` is the preprocessed scan the model actually sees; `overlay` is the
    JET heatmap superimposed with the notebook's 0.6/0.4 blend. Returning both
    lets the UI cross-fade between them without any misregistration."""
    base = np.uint8(255 * x[0, ..., 0])                     # what the model sees
    base_rgb = cv2.cvtColor(base, cv2.COLOR_GRAY2BGR)

    hm = cv2.resize(heat, (base.shape[1], base.shape[0]))
    hm = cv2.applyColorMap(np.uint8(255 * hm), cv2.COLORMAP_JET)

    blended = cv2.addWeighted(base_rgb, 0.6, hm, 0.4, 0)

    up = lambda im: cv2.resize(im, (size, size), interpolation=cv2.INTER_CUBIC)
    return _png_data_uri(up(base_rgb)), _png_data_uri(up(blended))


# ---------------------------------------------------------------- endpoints
@app.get("/")
def health():
    return {"status": "ok", "tumor_model": os.path.basename(TUMOR_MODEL.rstrip("\\/"))}


@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        data = await file.read()

        # MRI vs non-MRI. class 0 = MRI, class 1 = Non-MRI (alphabetical).
        raw = float(mri_detector.predict(preprocess_mri(data), verbose=0)[0][0])
        is_mri = raw < 0.5
        mri_confidence = (1.0 - raw) if is_mri else raw

        # Tumor classification (always computed; caller decides how to use it).
        probs = tumor.predict(preprocess_tumor(data), verbose=0)[0]
        idx = int(np.argmax(probs))

        return {
            "is_mri": bool(is_mri),
            "mri_confidence": round(mri_confidence * 100, 2),
            "prediction": TUMOR_LABELS[idx],
            "confidence": round(float(probs[idx]) * 100, 2),
            "probabilities": {
                lbl: round(float(p) * 100, 2) for lbl, p in zip(TUMOR_LABELS, probs)
            },
        }
    except Exception as e:  # noqa: BLE001
        return JSONResponse(status_code=500, content={"error": str(e)})


@app.post("/gradcam")
async def gradcam(file: UploadFile = File(...)):
    """Same as /predict, plus a Grad-CAM overlay showing where the model looked."""
    try:
        data = await file.read()

        raw = float(mri_detector.predict(preprocess_mri(data), verbose=0)[0][0])
        is_mri = raw < 0.5

        x = preprocess_tumor(data)
        probs = tumor.predict(x, verbose=0)[0]
        idx = int(np.argmax(probs))

        heat, _ = grad_cam(x, class_idx=idx)
        base_uri, overlay_uri = render_overlay(x, heat)

        return {
            "is_mri": bool(is_mri),
            "mri_confidence": round(((1.0 - raw) if is_mri else raw) * 100, 2),
            "prediction": TUMOR_LABELS[idx],
            "confidence": round(float(probs[idx]) * 100, 2),
            "probabilities": {
                lbl: round(float(p) * 100, 2) for lbl, p in zip(TUMOR_LABELS, probs)
            },
            "gradcam": overlay_uri,
            "base_image": base_uri,
            "gradcam_layer": GRADCAM_LAYER,
        }
    except Exception as e:  # noqa: BLE001
        return JSONResponse(status_code=500, content={"error": str(e)})


if __name__ == "__main__":
    import uvicorn

    port = int(os.environ.get("PORT", "8002"))
    uvicorn.run(app, host="127.0.0.1", port=port, log_level="warning")
