# Phase 1 – SK Traders (Complete)

## What’s included

- **Laravel 12** in `app/` with **MySQL** (DB name: **zuaaz** in `.env`)
- **Vue 3 + Inertia.js** (Laravel Breeze)
- **Tailwind CSS** with Open Sans (400, 600, 700), `rounded-2xl`, `shadow-soft`
- **Public routes**: `/`, `/about`, `/services`, `/products`, `/private-label`, `/how-it-works`, `/why-choose-us`, `/quality`, `/faq`, `/contact`
- **Migrations**: `categories`, `products`, `inquiries`, `faqs`, `seo_settings`, `homepage_sections` (+ default `users`, etc.)
- **Models**: Category, Product (relationship), Inquiry, Faq, SeoSetting, HomepageSection
- **Config**: `config/zuaaz.php` (WhatsApp, contact, address) + shared to frontend via Inertia
- **AppLayout**: Sticky header (logo, nav, “Request a Quote”), mobile hamburger, footer, **floating WhatsApp** (bottom right)

## Run the app

1. **Create MySQL database**
   ```bash
   mysql -u root -e "CREATE DATABASE zuaaz;"
   ```

2. **Configure `.env`** (already set in project; ensure DB matches):
   ```env
   DB_CONNECTION=mysql
   DB_DATABASE=zuaaz
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Migrations**
   ```bash
   cd app && php artisan migrate
   ```

4. **Frontend**
   ```bash
   npm install && npm run dev
   ```

5. **Serve**
   ```bash
   php artisan serve
   ```
   Open http://localhost:8000

## Optional: Lucide Icons

Phase 1 uses inline SVG for the WhatsApp button. For Lucide everywhere:

```bash
npm install lucide-vue-next
```

Then in components: `import { IconName } from 'lucide-vue-next';`

## Next: Phase 2

Homepage sections, static content, inquiry form, contact page, emails, SEO.
