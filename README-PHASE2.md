# Phase 2 – Public Site & Conversion (Complete)

## What’s included

### Homepage
- **Hero:** H1 “Private Label Gym Gear & Sportswear Manufacturer”, SEO keywords, Request a Quote (scroll to form) + WhatsApp Us buttons
- **Trust:** Tagline, short paragraph, country badges (USA, UK, Europe, Canada, Australia, GCC)
- **What We Do:** 6 service cards (Low MOQ, Custom Logo & Embroidery, Private Label & Packaging, OEM/ODM, Free Design Assistance, Sampling & Worldwide Shipping) with hover
- **How It Works:** 5 steps (Share idea → Sample design → Approval → Production → Shipment)
- **Why Choose Us:** 8-point grid with Lucide icons
- **CTA banner:** “GET QUOTATION” linking to contact#quote

### Static pages (content + CTA)
- **About Us:** Company story, facilities (Sialkot/Lahore), mission
- **Our Services:** 8 service cards + CTA
- **Private Label:** Copy + bullet list + CTA
- **How It Works:** Stepper component + CTA
- **Why Choose Us:** WhyChooseGrid + CTA
- **Quality Control:** 4 points (Pre-production sampling, In-process inspection, Final QC, Packaging verification) + CTA
- **FAQ:** Accordion (DB-driven when seeded, else default list) + meta
- **Contact Us:** Contact info (email, website, WhatsApp, Instagram, office, manufacturing) + **Inquiry form** (same as quote form)

### Inquiry system
- **Form fields:** Full Name, Company, Email, WhatsApp, Country, Product Interest, Estimated Quantity, Message, optional file (logo)
- **Backend:** `StoreInquiryRequest` (validation, honeypot `website`), `InquiryController@store` (store in DB, queue 2 emails), rate limit **5 per minute** (`throttle:5,1`)
- **Thank-you page:** `/inquiry/thank-you` with success message
- **File upload:** Stored in `storage/app/public/inquiries` (link via `php artisan storage:link`)

### Email
- **To admin:** `InquiryReceivedNotification` → `config('zuaaz.contact.email')` (default info@zuaazgear.com), queued
- **To client:** `InquiryAutoReply` – subject “Thank you for contacting Atlantic garden Furniture”, body “We have received your inquiry. Our team will respond within 24 hours.”, queued
- **Config:** Set `MAIL_MAILER`, `MAIL_HOST`, etc. in `.env`; use `QUEUE_CONNECTION=database` and run `php artisan queue:work` to send

### Security
- **CSRF:** Laravel default
- **Honeypot:** Hidden field `website`; validation `max:0` (reject if filled)
- **Rate limiting:** 5 inquiries per minute per IP
- **File validation:** `mimes:pdf,png,jpg,jpeg,ai,eps`, max 5MB

### SEO
- **Per-page:** `<title>` and `<meta name="description">` (and keywords on Home) via Inertia `Head`
- **Homepage title:** “Private Label Gym Gear Manufacturer | Sportswear Manufacturer Pakistan | Atlantic garden Furniture”
- **Organization schema:** JSON-LD in `app.blade.php` (name, url, email, address, sameAs)
- **Image alt:** Use in components where images are added later

## Run queue (for emails)

```bash
php artisan queue:work
```

Or use a process manager (Supervisor) in production.

## Seed FAQ (optional)

```bash
php artisan db:seed --class=FaqSeeder
```

Then the FAQ page uses DB content; otherwise it shows the default 7 questions.

## Next: Phase 3

Products & catalog (categories/products, listing, detail, inquiry CTA).
