# OneHealth+ model-serving microservice (CPU-only).
#
# Build context is the REPOSITORY ROOT so this image can copy ml_server/,
# which is the same code the project runs locally. There is deliberately no
# second copy of the inference logic inside deployment/.
#
#   docker build -f deployment/model/Dockerfile .
#
# Python 3.10 + TensorFlow 2.10 match the environment these saved models were
# produced and verified in. Do NOT move to TensorFlow >= 2.16: it ships Keras 3,
# which cannot load these Keras 2 SavedModel directories.
FROM python:3.10-slim

ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1 \
    TF_CPP_MIN_LOG_LEVEL=2

WORKDIR /app

# OpenCV runtime dependencies + curl for the healthcheck
RUN apt-get update && apt-get install -y --no-install-recommends \
        libglib2.0-0 \
        libsm6 \
        libxext6 \
        libxrender1 \
        curl \
    && rm -rf /var/lib/apt/lists/*

COPY deployment/model/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# The inference service itself - single source of truth.
COPY ml_server/model_server.py .

# Weights are mounted at run time (see docker-compose.yml). These defaults let
# the image also run standalone with a weights volume attached.
ENV TUMOR_MODEL=/app/weights/presys \
    MRI_MODEL=/app/weights/102mod \
    GRADCAM_LAYER=Conv5

EXPOSE 8000

HEALTHCHECK --interval=15s --timeout=5s --start-period=120s --retries=10 \
    CMD curl -f http://localhost:8000/ || exit 1

CMD ["uvicorn", "model_server:app", "--host", "0.0.0.0", "--port", "8000"]
