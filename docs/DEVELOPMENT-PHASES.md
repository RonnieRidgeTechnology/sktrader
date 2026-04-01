# Atlantic garden Furniture Website – Development Phases

**Tagline:** Your Brand. Our Production.  
**Type:** B2B Manufacturing Website (No Retail / No E-commerce)

Tasks from [TASKS.md](./TASKS.md) are grouped into **5 development phases**. Complete each phase before moving to the next (or as agreed with the team).

---

## Phase 1: Foundation & Setup  
**Goal:** Working Laravel + Vue stack, DB schema, routes, base layout, design system.

| # | Task |
|---|------|
| 1.1 | Set up Laravel 12 (latest stable), PHP 8.2+, MySQL 8+ |
| 1.2 | Configure `.env`, RESTful structure, Form Requests pattern |
| 1.3 | Set up Vue 3 + Inertia.js (or Vue SPA with API) |
| 1.4 | Install Tailwind CSS, Lucide Icons, Open Sans (400, 600, 700) |
| 1.5 | Define all public routes: `/`, `/about`, `/services`, `/products`, `/private-label`, `/how-it-works`, `/why-choose-us`, `/quality`, `/faq`, `/contact` |
| 1.6 | Create migrations: `users`, `categories`, `products`, `inquiries`, `faqs`, `seo_settings`, `homepage_sections` |
| 1.7 | Implement Category ↔ Product relationships and models |
| 1.8 | Base layout: header (logo, nav, “Request a Quote” button), footer, sticky header, mobile hamburger |
| 1.9 | Design system: dark + white theme, 2xl radius, shadows, transitions, typography hierarchy, whitespace |
| 1.10 | Floating WhatsApp button (bottom right); optional “Request Quote” (bottom left); WhatsApp numbers in config |

**Exit criteria:** App runs, routes resolve, layout and design tokens are in place, DB is migratable.

---

## Phase 2: Public Site & Conversion  
**Goal:** Homepage, static pages, inquiry form, contact info, basic SEO.

| # | Task |
|---|------|
| 2.1 | **Homepage:** Hero (H1 + keywords, Request Quote + WhatsApp buttons), Trust section, What We Do (reusable service component + hover), How It Works (steps), Why Choose Us (grid + Lucide), CTA banner “GET QUOTATION” |
| 2.2 | **Static pages:** About, Our Services, Private Label, How It Works, Why Choose Us — content + layout |
| 2.3 | **Quality Control:** Static content (pre-production sampling, in-process inspection, final QC, packaging verification) |
| 2.4 | **Contact:** Display email, website, WhatsApp, Instagram, corporate office, manufacturing location; embed same form as inquiry |
| 2.5 | **Inquiry system:** Table `inquiries`; form (name, company, email, WhatsApp, country, product interest, quantity, message, logo upload); validation; store in DB; redirect to thank-you + success message |
| 2.6 | **Email:** SMTP in `.env`; queue; send to info@zuaazgear.com; auto-reply to client (“Thank you for contacting Atlantic garden Furniture”, “We have received your inquiry. Our team will respond within 24 hours.”); retry failed |
| 2.7 | **Security (form):** CSRF, file upload validation, rate limiting, honeypot or reCAPTCHA |
| 2.8 | **SEO (public):** Per-page meta title, description, keywords; Open Graph; canonical; Organization + FAQ schema; homepage title/description as per PRD; image alt tags |

**Exit criteria:** All public pages live, inquiry form works and emails send, basic SEO and schema in place.

---

## Phase 3: Products & Catalog  
**Goal:** Dynamic categories/products, listing, detail, filters, inquiry CTA.

| # | Task |
|---|------|
| 3.1 | Seed categories: Gym Gear (Weight Lifting Belts, Lever Belts, Wrist Wraps, Knee Sleeves, Elbow Sleeves, Lifting Straps, Gym Gloves, Resistance Bands, Barbell Pads, Ankle Straps, Gym Bags, Yoga Accessories), Sportswear (Gym T-Shirts, Tank Tops, Hoodies, Tracksuits, Shorts, Leggings, Compression Wear) |
| 3.2 | Products listing page with category filter |
| 3.3 | Product detail page (name, short/long description, image, gallery) |
| 3.4 | “Inquiry” / “Request Quote” button on each product (link to form or scroll to form with product context) |
| 3.5 | Eager loading and optimized queries for product/category pages |
| 3.6 | Lazy loading for product images |

**Exit criteria:** Products and categories are manageable via DB/seeders, listing and detail work, inquiry CTA on every product.

---

## Phase 4: Admin Panel & Operations  
**Goal:** Secure admin, manage content, view/export inquiries, emails.

| # | Task |
|---|------|
| 4.1 | Admin auth (Laravel Breeze or Sanctum), admin-only role and route protection |
| 4.2 | Admin dashboard UI (layout, navigation) |
| 4.3 | **Inquiries:** List view, CSV export |
| 4.4 | **Products:** CRUD (create, edit, delete, image/gallery upload) |
| 4.5 | **Categories:** CRUD |
| 4.6 | **FAQs:** CRUD for FAQ content |
| 4.7 | **SEO:** Manage SEO settings and meta tags (global + per page) |
| 4.8 | **Homepage:** Update homepage sections (e.g. hero, trust, services, steps, CTA) |
| 4.9 | Admin notification email template when new inquiry is received |
| 4.10 | XSS protection and admin route middleware |

**Exit criteria:** Admin can log in, manage products/categories/FAQs, view and export inquiries, update SEO and homepage content.

---

## Phase 5: Polish & Launch  
**Goal:** Performance, security hardening, sitemap/robots, production build.

| # | Task |
|---|------|
| 5.1 | Production config: cache, route caching, `.env` for production |
| 5.2 | Minified CSS/JS, no blocking JS, target page load &lt; 2 seconds |
| 5.3 | Sitemap.xml auto-generation |
| 5.4 | Robots.txt configured |
| 5.5 | Image optimization (e.g. intervention/image or similar) |
| 5.6 | Production-ready build script (assets, migrate, cache) |
| 5.7 | Final security review: CSRF, XSS, uploads, rate limits, spam protection |
| 5.8 | Optional: multi-language structure, blog stub, catalog PDF, analytics/CRM hooks (per PRD scalability) |

**Exit criteria:** Site is fast, secure, and deployable with one clear build/deploy process.

---

## Phase Overview

| Phase | Focus | Main deliverables |
|-------|--------|-------------------|
| **1** | Foundation & Setup | Laravel + Vue stack, DB, routes, layout, design system |
| **2** | Public Site & Conversion | Homepage, static pages, inquiry form, contact, emails, SEO |
| **3** | Products & Catalog | Categories/products, listing, detail, filters, inquiry CTA |
| **4** | Admin & Operations | Auth, inquiries, CRUD, SEO/homepage management, notifications |
| **5** | Polish & Launch | Performance, security, sitemap, production build |

---

## Dependency order

- **Phase 1** must be done first (base for everything).
- **Phase 2** can start once routes and layout (1.8–1.10) are in place; inquiry DB and email (2.5–2.6) unblock Contact and product inquiry CTA later.
- **Phase 3** needs Phase 1 DB and Phase 2 form; can overlap with Phase 4.
- **Phase 4** needs Phase 1 (auth, DB) and Phase 2 (inquiries table).
- **Phase 5** is last, after all features are in place.

Use [TASKS.md](./TASKS.md) for the full checkbox list; use this document for planning and tracking by phase.
