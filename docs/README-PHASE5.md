# Phase 5: Polish & Launch – Summary

## Done

### 5.1–5.2 Production config & build
- **Config cache:** `php artisan config:cache` (see deploy script).
- **.env.example:** Production notes (APP_ENV=production, APP_DEBUG=false, CACHE_STORE).
- **Frontend:** Vite production build minifies CSS/JS; single entry `resources/js/app.js` in `app.blade.php` for correct production loading.

### 5.3 Sitemap
- **Route:** `GET /sitemap.xml` → `SitemapController@index`.
- **Content:** Static pages (home, about, services, products, contact, faq, etc.) + all active product URLs with `lastmod`.
- **Headers:** `Content-Type: application/xml; charset=UTF-8`, cache 1 hour.

### 5.4 Robots.txt
- **Route:** `GET /robots.txt` → `RobotsController@index`.
- **Content:** Disallow /admin, /dashboard, /profile, /login, /register, /inquiry; Allow /; Sitemap URL from `APP_URL`.

### 5.5 Image optimization
- **Service:** `App\Services\ImageOptimizationService` (PHP GD).
- **Behaviour:** Resizes images wider than 1200px, JPEG/PNG/WebP; used for admin category and product uploads (main image + gallery).
- **Validation:** Admin image uploads: `image|mimes:jpeg,png,jpg,webp|max:5120`.

### 5.6 Deploy script
- **Scripts:** `deploy.sh` (Unix), `deploy.ps1` (Windows) in project root (`app/`).
- **Steps:** `composer install --no-dev`, `npm ci`, `npm run build`, `php artisan migrate --force`, `php artisan config:cache`, `php artisan view:cache`.
- **Note:** `route:cache` not used (closure-based routes).

### 5.7 Security review
- **Doc:** `docs/SECURITY-REVIEW.md` (CSRF, XSS, file uploads, rate limits, honeypot, admin protection).
- **Admin uploads:** Image type and size validation added.

## Tests
- **SitemapRobotsTest:** Sitemap returns 200 + XML; robots returns 200 + Sitemap line.
- **Full suite:** 27 tests (auth, profile, sitemap, robots).

## How to deploy
1. Set production `.env` (APP_ENV=production, APP_DEBUG=false, APP_URL, DB_*, etc.).
2. Run `./deploy.sh` or `.\deploy.ps1` from the Laravel root (`app/`).
3. Point the web server to `app/public`; ensure queue worker runs if using queues.
