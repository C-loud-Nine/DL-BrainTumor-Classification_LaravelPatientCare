# ML Model Servers (Brain-Tumor MRI system)

The Laravel app does **not** run the ML models itself. It POSTs uploaded MRI
images to three FastAPI services (see `.env` → `FASTAPI_URL`, `FASTAPI_URL_2`,
`FASTAPI_URL_3`). This folder contains those services.

## Models used

Trained TensorFlow SavedModels live under `C:\Users\Shafi.09\AnocondaProjects`:

| Purpose            | Model dir                                   | Input        | Output            |
|--------------------|---------------------------------------------|--------------|-------------------|
| Tumor classifier 1 | `BrainT\Final training\models\presys`       | 150×150×1 gray | 4-class softmax  |
| Tumor classifier 2 | `BrainT\Final training\models\1sys`         | 150×150×1 gray | 4-class softmax  |
| MRI vs non-MRI     | `MRI_Image_Classificaion\102mod`            | 128×128×3 RGB  | sigmoid          |

Classes: `glioma, meningioma, pituitary, notumor`.
MRI detector: class 0 = MRI, class 1 = Non-MRI → `is_mri = sigmoid < 0.5`.

Preprocessing is replicated from the training notebooks in
`Desktop\GitHub\DL-Brain_Tumor_Classification` and `Desktop\GitHub\BrainTumor_ML`:
- Tumor: grayscale → CLAHE(3.0, 8×8) → medianBlur(3) → resize 150 → /255
- MRI:   RGB → resize 128 → /255

## Port → model wiring

| Port | Tumor model | Role                                        |
|------|-------------|---------------------------------------------|
| 8001 | presys      | "forceful" classify (used after non-MRI warning) |
| 8002 | presys      | primary (MRI detect + classify)             |
| 8003 | 1sys        | second opinion for the dual-model pages     |

## Run

Needs the `tensorflow` conda env (TF 2.10, keras, opencv, pillow, fastapi, uvicorn).

```bat
ml_server\start_models.bat        REM opens 3 windows on 8001/8002/8003
```

Or one server manually:

```bash
PORT=8002 TUMOR_MODEL="C:/Users/Shafi.09/AnocondaProjects/BrainT/Final training/models/presys" \
  C:/Users/Shafi.09/anaconda3/envs/tensorflow/python.exe model_server.py
```

Then start Laravel: `php artisan serve`.

## Response shape

```json
{ "is_mri": true, "mri_confidence": 62.59,
  "prediction": "glioma", "confidence": 91.53,
  "probabilities": { "glioma": 91.53, "meningioma": 5.2, "pituitary": 1.81, "notumor": 1.45 } }
```
