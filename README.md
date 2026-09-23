# CitasYa Monorepo

## Overview
**CitasYa** is an on-demand booking, appointment scheduling, and multi-vendor marketplace platform for all professional service providers in Bolivia—including medical clinics, dental practices, beauty & spa centers, barber shops, notary offices, law firms, auto workshops, and independent consultants.

---

## Workspace Structure
- **`backend/`**: Laravel 10 REST API, Admin Control Panel, Staging/Curation Pipeline, and Web Landing Page.
- **`app-customer/`**: Flutter mobile & web app for customers (service discovery, appointment booking, Simple QR payment, notifications).
- **`app-owner/`**: Flutter mobile & web app for business owners and specialists (booking management, calendar, availability, earnings).
- **`docs/`**: Branding assets, system architecture, and API specifications.

---

## Live System URLs & Deployment Endpoints
- **Main Web Landing Page:** [https://citasya.clubemkt.online/](https://citasya.clubemkt.online/)
- **Admin Control Panel:** [https://citasya.clubemkt.online/login](https://citasya.clubemkt.online/login)
- **Business Directory:** [https://citasya.clubemkt.online/negocios](https://citasya.clubemkt.online/negocios)
- **Admin Curation Queue:** [https://citasya.clubemkt.online/solicitudes](https://citasya.clubemkt.online/solicitudes)
- **Business Web App (Owner):** [https://citasya.clubemkt.online/owner/](https://citasya.clubemkt.online/owner/)
- **Customer Web App:** [https://citasya.clubemkt.online/app/](https://citasya.clubemkt.online/app/)

### 📱 Release Downloads
- **Customer Android APK:** [https://citasya.clubemkt.online/downloads/citasya-customer.apk](https://citasya.clubemkt.online/downloads/citasya-customer.apk)
- **Owner Android APK:** [https://citasya.clubemkt.online/downloads/citasya-owner.apk](https://citasya.clubemkt.online/downloads/citasya-owner.apk)

---

## Key System Features & Architecture

### 1. Universal Business Directory
- Fully generalized schema supporting any appointment-based business.
- All salon-only terminology removed across UI, APIs, and locale strings.

### 2. Automated Directory Scraper & Admin Curation Pipeline
- **Scraper Command:** `php artisan scrape:directory {url?}`
- **Staging Area:** New listings land as `curation_status: pending` and `accepted: 0`.
- **Curation Queue:** Admins review, edit, or publish staged businesses at `/solicitudes`.
- **API Guard:** `AcceptedCriteria` ensures only `curation_status === 'approved'` records hit the live directory and mobile apps.
- **Task Scheduler:** Configured in `Kernel.php` to run daily at `03:00 AM`.

### 3. Bolivia Payment & Messaging Integrations
- **Simple QR Bolivia:** Instant QR generation for prepayment and deposit collection.
- **WhatsApp Automation:** 1-click confirmation links and automated reminder templates.
- **Localization:** Default currency `Bs.` (BOB), default locale `es` (Spanish), timezone `America/La_Paz` (UTC-4).

### 4. High-Performance Web Engine
- Flutter Web compiled using the **HTML renderer** (`--web-renderer html`) to eliminate WebAssembly bundle overhead and prevent browser `VideoFrame` memory leaks.
- Nginx gzip compression and asset cache-busting configured.

---

## Configuration Defaults
- **Admin Email:** `hudsonargollo@gmail.com`
- **Default Currency:** `Bs` (BOB)
- **Timezone:** `America/La_Paz` (UTC-4)
- **Country Code:** `BO` (+591)
- **Default Locale:** `es` (Spanish)

---

## Development & Local Docker Commands
```bash
# Start local containers
docker compose up -d

# Scrape & Stage Directory Listings
docker exec citasya-backend php artisan scrape:directory

# Build Owner Web App
docker exec citasya-flutter-builder bash -c "cd /workspace/app-owner && flutter build web --web-renderer html --base-href /owner/ --release"

# Build Customer Web App
docker exec citasya-flutter-builder bash -c "cd /workspace/app-customer && flutter build web --web-renderer html --base-href /app/ --release"
```

---

## Tech Stack
- **Backend:** Laravel 10 / PHP 8.2 / MySQL 8.0 / AdminLTE / Symfony DomCrawler
- **Customer App:** Flutter 3.24 (Dart) / GetX / HTML Renderer
- **Owner App:** Flutter 3.24 (Dart) / GetX / HTML Renderer
- **Infrastructure:** Docker / Nginx / Cloudflare
