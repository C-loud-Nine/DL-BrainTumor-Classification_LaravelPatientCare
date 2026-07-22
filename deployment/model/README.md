# OneHealth+ model service

FastAPI microservice performing MRI validation, brain-tumour classification
and Grad-CAM generation.

**The service code is not in this folder.** It is
[`ml_server/model_server.py`](../../ml_server/model_server.py) at the repository
root — the same file the project runs locally. `deployment/model/Dockerfile`
copies it in, so there is one implementation rather than two that can drift.

## Models

Three TensorFlow **SavedModel directories** (not `.h5` files):

| Directory | Role | Input | Output |
|-----------|------|-------|--------|
| `presys`  | tumour classifier (primary)        | 150×150×1 grayscale | 4-class softmax |
| `1sys`    | tumour classifier (second opinion) | 150×150×1 grayscale | 4-class softmax |
| `102mod`  | MRI vs non-MRI validator           | 128×128×3 RGB       | sigmoid |

Class order is `glioma, meningioma, pituitary, notumor` — the order used during
training. Do not sort it alphabetically; `pituitary` and `notumor` would swap.

The validator's classes were assigned alphabetically by `flow_from_directory`,
so **class 0 = MRI, class 1 = Non-MRI**, giving `is_mri = sigmoid < 0.5`.
Confirmed empirically: a non-MRI photograph scores ≈0.9999 while genuine brain
scans score below 0.5.

Preprocessing mirrors each model's training pipeline:

- **Tumour**: grayscale → CLAHE(clipLimit 3.0, 8×8) → medianBlur(3) → resize 150 → `/255`
- **Validator**: RGB → resize 128 → `/255` (no CLAHE, no grayscale)

The two models take different input sizes, so each is preprocessed separately
from the same upload.

## Staging the weights

Weights are not tracked in git. Stage them into `weights/` before building:

```powershell
powershell -ExecutionPolicy Bypass -File deployment\collect_weights.ps1
```

Resulting layout:

```
deployment/model/weights/
├── presys/    saved_model.pb, variables/, ...
├── 1sys/      saved_model.pb, variables/, ...
└── 102mod/    saved_model.pb, variables/, ...
```

## Configuration

| Variable | Default | Description |
|----------|---------|-------------|
| `TUMOR_MODEL` | `/app/weights/presys` | SavedModel dir for the tumour classifier |
| `MRI_MODEL` | `/app/weights/102mod` | SavedModel dir for the MRI validator |
| `GRADCAM_LAYER` | `Conv5` | Conv layer Grad-CAM reads (models expose `Conv1`–`Conv5`) |

Compose runs three containers from this one image, differing only by
`TUMOR_MODEL`, because the dual-model pages compare two classifiers.

## Endpoints

| Method | Path | Purpose |
|---|---|---|
| `GET`  | `/` | Health / readiness; reports which tumour model is loaded |
| `POST` | `/predict` | Classification for an uploaded scan |
| `POST` | `/gradcam` | Classification **plus** a Grad-CAM overlay |

Both accept a multipart `file` field. Response shape — these key names are what
the Laravel controllers read, so they are part of the contract:

```json
{
  "is_mri": true,
  "mri_confidence": 62.59,
  "prediction": "glioma",
  "confidence": 91.53,
  "probabilities": { "glioma": 91.53, "meningioma": 5.2, "pituitary": 1.81, "notumor": 1.45 },
  "gradcam": "data:image/png;base64,...",
  "base_image": "data:image/png;base64,...",
  "gradcam_layer": "Conv5"
}
```

`confidence` and `mri_confidence` are percentages (0–100).

`/gradcam` additionally returns `base_image`: the preprocessed scan the model
actually sees, pixel-aligned with `gradcam`. The UI cross-fades between the two,
which only works because both are rendered at the same size from the same
preprocessing. Images come back inline as data URIs, so the caller decides where
to persist them — the model container needs no writable volume and no published
port.

## Running standalone

From the repository root:

```bash
docker build -f deployment/model/Dockerfile -t onehealth_model .
docker run --rm -p 8002:8000 \
  -v "$(pwd)/deployment/model/weights:/app/weights:ro" \
  onehealth_model
```

## Version constraint

TensorFlow is pinned to **2.10.1** (Keras 2). Do not upgrade to ≥ 2.16: those
releases default to Keras 3, which cannot load these Keras 2 SavedModels.
