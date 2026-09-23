# CitasYa Backend & API

## Overview
The CitasYa backend is a Laravel 10 application providing:
- RESTful APIs for Customer and Owner Flutter applications.
- Admin Control Panel (Blade + AdminLTE + DataTables).
- Web Landing Page and Marketing Portal (`/`).
- Automated Scraper & Directory Curation Engine.

---

## Key Routes & Slugs
- `/` - Marketing Landing Page
- `/login` - Admin & Vendor Login
- `/solicitudes` - Staging & Curation Queue (Requested Businesses)
- `/negocios` - Directory of Active Businesses
- `/niveles` - Business Level Management
- `/owner/` - Owner Flutter Web Application
- `/app/` - Customer Flutter Web Application
- `/downloads/` - Android APK Distribution

---

## Artisan Commands
```bash
# Directory Scraper & Staging Engine
php artisan scrape:directory {url?}

# Clear All System Caches
php artisan config:clear && php artisan view:clear && php artisan cache:clear

# Run Migrations
php artisan migrate --force
```

---

## Database Curation Workflow
All scraped listings are inserted with `curation_status = 'pending'` and `accepted = false`. They appear in the Curation Queue at `/solicitudes`. When an admin approves or edits a record, `curation_status` is updated to `'approved'`, releasing it to the live directory and API.
