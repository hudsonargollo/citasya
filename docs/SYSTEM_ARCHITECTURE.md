# CitasYa System Architecture & Engineering Documentation

## 1. System Overview
**CitasYa** is a high-performance, multi-tenant scheduling and directory platform built specifically for service providers in Bolivia. The system handles end-to-end appointment discovery, automated WhatsApp confirmation, Simple QR payment processing, and admin curation.

---

## 2. Infrastructure Architecture
```
                         +-----------------------+
                         |      Cloudflare       |
                         |   (SSL & CDN Layer)   |
                         +-----------+-----------+
                                     |
                                  HTTP:80
                                     v
                         +-----------------------+
                         |     Core Nginx        |
                         |  Reverse Proxy :80    |
                         +-----------+-----------+
                                     |
                         +-----------+-----------+
                         |                       |
                  proxy_pass:8085         proxy_pass:8085
                         |                       |
                         v                       v
               +-------------------+   +-------------------+
               |  citasya-backend  |   |    MySQL 8.0      |
               | (Laravel 10 API)  |---| (citasya_db :3307)|
               +-------------------+   +-------------------+
                         |
      +------------------+------------------+
      |                  |                  |
      v                  v                  v
+-----------+      +-----------+      +-----------+
| Landing   |      | Owner Web |      | Customer  |
| Page (/)  |      | (/owner/) |      | Web (/app)|
+-----------+      +-----------+      +-----------+
```

---

## 3. Database & Curation Pipeline

### Schema Structure for Salons / Businesses (`salons` table)
| Column | Type | Default | Description |
|---|---|---|---|
| `id` | BigInt | Auto-Increment | Primary Key |
| `name` | Json/String | Translatable | Business Name |
| `salon_level_id` | Foreign Key | 2 | Subscription / Category Level |
| `address_id` | Foreign Key | Address ID | Physical location record |
| `phone_number` | String | Cleaned Digits | Phone / WhatsApp Contact |
| `curation_status` | Enum | `pending` | Staging status (`pending`, `approved`, `rejected`) |
| `accepted` | Boolean | `false` | Admin approval flag |
| `available` | Boolean | `true` | Listing active status |

### Curation Criteria (`App\Criteria\Salons\AcceptedCriteria`)
```php
public function apply($model, RepositoryInterface $repository): mixed
{
    return $model->where('salons.accepted', '1')
                 ->where('salons.curation_status', 'approved');
}
```

### Directory Scraper Workflow (`php artisan scrape:directory`)
1. **Fetch & Parse:** Requests target HTML using `GuzzleHttp` / `Symfony\Component\DomCrawler\Crawler`.
2. **Clean Data:** Strips HTML, normalizes Bolivia phone numbers (`+591...`), generates slugged fallback emails.
3. **Deduplication:** Checks existing records by `phone_number` or `name` before saving.
4. **Staging:** Creates `Salon` record with `curation_status = 'pending'` and `accepted = false`.
5. **Curation:** Appears in Admin Panel at `/solicitudes`. When approved by admin, `curation_status` becomes `'approved'`.

---

## 4. Web Engine & Rendering Strategy
- **Flutter Web Engine:** Compiled with `--web-renderer html`.
- **Performance Benefits:**
  - Avoids downloading heavy `canvaskit.wasm` (~7.5MB payload eliminated).
  - Prevents Chromium `VideoFrame` garbage collection memory leaks on camera and image picker interactions.
  - Initial load time reduced from ~8s to <1s.

---

## 5. Security & Permission Management
- Role-based Access Control (RBAC) via `spatie/laravel-permission`.
- System Roles: `admin`, `provider`, `customer`.
- Strict storage ownership (`www-data:www-data 775`) on `storage/` and `bootstrap/cache/`.
