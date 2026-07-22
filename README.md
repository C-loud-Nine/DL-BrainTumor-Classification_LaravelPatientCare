# OneHealth+

**An open-source platform for verified, explainable brain tumor classification with clinician-in-the-loop workflow management.**

DOI: https://doi.org/10.5281/zenodo.21432960
License: MIT

OneHealth+ embeds a previously published lightweight brain tumor classifier, **DSCBAM-Net**, inside a verification-first clinical pipeline. Every uploaded image is authenticated as a genuine MRI slice before classification, every prediction is paired with a Grad-CAM saliency overlay, and no diagnosis reaches a patient until a credentialed clinician confirms or overrides it — with every decision and timestamp permanently logged. The platform is designed to run on CPU-only commodity hardware, removing the GPU dependency that blocks adoption in resource-constrained settings.

> This repository accompanies the SoftwareX article describing OneHealth+. The classifier itself is described and evaluated separately; see the [citation](#citation) below.

---

## Table of contents

- [Key features](#key-features)
- [Architecture](#architecture)
- [Requirements](#requirements)
- [Quick start (Docker)](#quick-start-docker)
- [Manual setup (without Docker)](#manual-setup-without-docker)
- [Configuration](#configuration)
- [API reference](#api-reference)
- [Project structure](#project-structure)
- [Validation scenarios](#validation-scenarios)
- [Limitations](#limitations)
- [License](#license)
- [Citation](#citation)
- [Contact](#contact)

---

## Key features

- **Dual-stage inference** — an MRI validator rejects non-medical or corrupt input before the classifier runs, so the system fails safely on out-of-distribution images.
- **Explainable predictions** — each result includes a Grad-CAM heatmap highlighting the regions most influential to the model's decision.
- **Mandatory clinician verification** — every AI prediction is held in a *Pending Doctor Verification* state until a clinician confirms or overrides it; the verdict, notes, and timestamp are persisted for audit.
- **Role-based access** — separate patient, doctor, and administrator dashboards, enforced at the route level.
- **Clinician workflow management** — specialisation-filtered appointment booking, automated PDF reporting, and a persistent per-patient report history.
- **CPU-only, containerised deployment** — the full stack runs from a single `docker compose up`, with no GPU required.

---

## Architecture

OneHealth+ follows a three-tier architecture:

| Tier | Component | Responsibility |
|------|-----------|----------------|
| Presentation | Bootstrap 5 web client | Role-specific dashboards |
| Application | Laravel (PHP 8) backend | Auth, business logic, persistence, orchestration |
| Application | FastAPI (Python 3.9) microservice | Model serving: MRI validation, classification, Grad-CAM |
| Persistence | MySQL 8.0 | Relational data store |

For every classification request, the Laravel backend forwards the uploaded image to the FastAPI microservice, receives a structured JSON response, and stores both the AI prediction and the clinician's final verdict.

---

## Requirements

**Containerised (recommended):**
- Docker Engine 20.10+
- Docker Compose 1.29+

**Manual setup:**
- PHP 8.x with Composer
- Python 3.9+
- MySQL 8.0+
- Node.js (for frontend asset compilation, if applicable)

The stack runs on Linux (recommended), Windows, or macOS.

---

## Quick start (Docker)

```bash
# 1. Clone the repository
git clone https://github.com/C-loud-Nine/OneHealthPlus.git
cd OneHealthPlus

# 2. Create your environment file
cp .env.example .env
#    Edit .env to set database credentials and app keys (see Configuration)

# 3. Build and start all services
docker compose up --build

# 4. Run database migrations (first run only)
docker compose exec web php artisan migrate

# 5. Open the application
#    http://localhost:80
```

The Compose configuration defines three services:

- `web` — Laravel served via Nginx
- `model` — FastAPI with TensorFlow
- `db` — MySQL 8.0

Only the `web` service is exposed to the host; `model` and `db` communicate over the internal Compose network.

---

## Manual setup (without Docker)

<details>
<summary>Backend (Laravel)</summary>

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
</details>

<details>
<summary>AI microservice (FastAPI)</summary>

```bash
cd model
python -m venv venv
source venv/bin/activate        # Windows: venv\Scripts\activate
pip install -r requirements.txt
uvicorn app:app --host 0.0.0.0 --port 8000
```
</details>

Ensure the Laravel `.env` points to the running FastAPI service (see Configuration).

---

## Configuration

Key environment variables in `.env`:

| Variable | Description |
|----------|-------------|
| `APP_KEY` | Laravel application key (`php artisan key:generate`) |
| `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | MySQL connection |
| `MODEL_SERVICE_URL` | Base URL of the FastAPI microservice (e.g. `http://model:8000`) |

Never commit a populated `.env` — commit only `.env.example`.

---

## API reference

### `POST /predict`

Accepts an image and returns a validity flag, predicted class, confidence score, and Grad-CAM URL.

**Request**
```
POST /predict
Content-Type: multipart/form-data
{ "file": <MRI image> }
```

**Response** `200 OK`
```json
{
  "is_valid_mri": true,
  "predicted_class": "pituitary",
  "confidence": 0.93,
  "gradcam_url": "/static/gradcam/<request-id>.png"
}
```

Field values above are representative examples, not measured benchmarks. Non-medical or corrupt input returns a rejection response with an explanatory message and no classification.

---

## Project structure

```
OneHealthPlus/
├── app/                 # Laravel application code
├── config/              # Laravel configuration
├── database/            # Migrations and seeders
├── public/              # Web root
├── resources/           # Blade views, frontend assets
├── routes/              # Route definitions
├── model/               # FastAPI microservice (validator, classifier, Grad-CAM)
├── docker-compose.yml   # Service orchestration
├── Dockerfile           # Container build(s)
├── .env.example         # Environment template
└── README.md
```

> Adjust this section to match the actual repository layout before publishing.

---

## Validation scenarios

The following functional scenarios are reproducible from this repository using the supplied test scripts:

| ID | Target | Expected evidence |
|----|--------|-------------------|
| V1 | User registration | User record created with hashed credentials |
| V2 | Authentication | Session issued; role-appropriate redirect |
| V3 | MRI upload | Image stored; microservice called; result displayed |
| V4 | MRI validation | Non-medical image rejected with message |
| V5 | Classification | Class, confidence, and Grad-CAM URL returned |
| V6 | Doctor verification | Status updated; notes and timestamp persisted |
| V7 | PDF report | Structured PDF generated and accessible |
| V8 | Appointment workflow | Status transitions and notifications correct |
| V9 | Deployment | All three services healthy after `docker compose up` |

---

## Limitations

This release is an **academic prototype** intended for evaluation, customisation, and extension — not direct clinical deployment. It does not yet implement encryption at rest, HL7 FHIR / DICOM interoperability, or multidisciplinary review. Institutions adopting the platform for real patient care must conduct their own credentialing, security, and regulatory review. Concurrency, latency, and scalability under production load have not been formally benchmarked.

---

## License

Released under the [MIT License](LICENSE).

---

## Citation


```bibtex

@inproceedings{shafi2025dscbam,
  title     = {DSCBAM-Net: A Lightweight Neural Network with Grouped
               Depthwise-Separable CBAM for Automated Brain Tumor Classification},
  author    = {Shafi, Abdullah Al and Suma, Sumaiya Rahim and Ahsan, S. M. M.},
  booktitle = {2025 7th International Conference on Electrical Information
               and Communication Technology (EICT)},
  year      = {2025},
  pages     = {1--6},
  doi       = {10.1109/EICT68394.2025.11355571}
}
```

---

## Contact

**Abdullah Al Shafi** — abdullah.shafi99@gmail.com
Department of Computer Science and Engineering, Khulna University of Engineering & Technology, Khulna 9203, Bangladesh
