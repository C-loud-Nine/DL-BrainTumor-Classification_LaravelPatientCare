# Deployment

Docker Compose stack for OneHealth+.

| Service | Image | Published | Purpose |
|---|---|---|---|
| `web` | built from `deployment/Dockerfile` | `:8080` | Laravel app (Nginx + PHP-FPM 8.2) |
| `model1` | `onehealth_model` | internal | classifier `presys` → `FASTAPI_URL` |
| `model2` | `onehealth_model` | internal | classifier `presys` → `FASTAPI_URL_2` |
| `model3` | `onehealth_model` | internal | classifier `1sys` → `FASTAPI_URL_3` |
| `db` | `mysql:8.0` | internal | database `hospital` |

Only `web` is reachable from the host.

## Why three model services

The application calls three separate endpoints. `usermri3` and `doctormri3`
post the *same* scan to `FASTAPI_URL_2` **and** `FASTAPI_URL_3` and show the two
predictions side by side, so a single model service cannot serve this app.
`model1` and `model2` load the same classifier but stay separate because the
"forceful classification" path is wired to `FASTAPI_URL` independently.

All three run the identical image; only `TUMOR_MODEL` differs.

## Quick start

```bash
# 1. Stage the trained weights (they live outside the repo)
powershell -ExecutionPolicy Bypass -File deployment\collect_weights.ps1

# 2. Configure
cd deployment
cp .env.example .env

# 3. Generate an APP_KEY and paste it into .env
docker compose run --rm web php artisan key:generate --show

# 4. Build and start
docker compose up -d --build
```

First start pulls TensorFlow and loads two models per container, so allow a few
minutes before the health checks pass. Then open <http://localhost:8080>.

```bash
docker compose ps          # health
docker compose logs -f web
docker compose down        # stop (volumes retained)
```

## Build context

Both images build from the **repository root**, not from `deployment/`:

- `web` needs `composer.json`, `artisan`, `app/`, `resources/`
- `model` needs `ml_server/model_server.py`

That is why `docker-compose.yml` sets `context: ..` with an explicit
`dockerfile:` path, and why `.dockerignore` lives at the repository root — a
`.dockerignore` is only honoured at the context root.

## Data that must persist

| Volume | Mount | Holds |
|---|---|---|
| `db_data` | `/var/lib/mysql` | database |
| `app_uploads` | `/var/www/html/public/uploads` | uploaded scans + Grad-CAM overlays |
| `app_storage` | `/var/www/html/storage` | logs, sessions, compiled views |

`public/uploads` **must** be a volume: uploaded scans and every generated
Grad-CAM overlay are written there at runtime and referenced by stored reports.

## Notes

- The entrypoint runs `php artisan migrate --force` on start and clears cached
  config. Config caching is deliberately **not** used: the controllers call
  `env()` directly, and `config:cache` makes `env()` return `null` outside
  config files — which would silently break every model call.
- Model containers return Grad-CAM images inline as base64 data URIs. The
  Laravel side decides where to persist them, so the model containers need no
  writable volume and no published port.
- TensorFlow is pinned below 2.16 — see `model/README.md`.

## Local development without Docker

The same model service runs directly from `ml_server/`:

```bat
ml_server\start_models.bat
php artisan serve
```

That path uses ports 8001/8002/8003 on localhost, matching the `FASTAPI_URL*`
values in the project's local `.env`.
