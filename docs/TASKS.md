# Atlantic garden Furniture Website – Task List

**Tagline:** Your Brand. Our Production.  
**Type:** B2B Manufacturing Website (No Retail / No E-commerce)

**Development phases:** See [DEVELOPMENT-PHASES.md](./DEVELOPMENT-PHASES.md) for tasks grouped into 5 phases (Foundation → Public Site → Products → Admin → Polish & Launch).

---

## 1. Technology Stack

### Backend
- [ ] Set up Laravel (latest stable) with PHP 8.2+
- [ ] Configure MySQL 8+ connection
- [ ] Implement RESTful architecture
- [ ] Form validation via Laravel Form Requests
- [ ] Configure mail (SMTP: Gmail / Zoho / Mailgun) in `.env`
- [ ] Enable and configure queue (database/redis) for email sending
- [ ] Production `.env` and config (cache, route caching)
- [ ] Image optimization support (intervention/image or similar)
- [ ] Sitemap auto-generation
- [ ] Deployment-ready build and cache config

### Frontend
- [ ] Set up Vue 3
- [ ] Integrate Inertia.js OR Vue SPA with Laravel API
- [ ] Install and configure Tailwind CSS
- [ ] Add Lucide Icons
- [ ] Add Open Sans (Google Fonts), weights 400, 600, 700
- [ ] Ensure fully responsive, mobile-first layout
- [x] SEO-friendly HTML structure (one H1 per page, H2/H3 for sections), meta title/description, keywords in alts/footer/URL slugs

---

## 2. Website Structure (Routing)

### Public routes
- [ ] Home → `/`
- [ ] About Us → `/about`
- [ ] Our Services → `/services`
- [ ] Products → `/products`
- [ ] Private Label → `/private-label`
- [ ] How It Works → `/how-it-works`
- [ ] Why Choose Us → `/why-choose-us`
- [ ] Quality Control → `/quality`
- [ ] FAQ → `/faq`
- [ ] Contact Us → `/contact`

---

## 3. Global Website Features

### Header
- [ ] Logo on the left
- [ ] Navigation menu links
- [ ] “Request a Quote” primary button (highlighted)
- [ ] Sticky header on scroll
- [ ] Mobile hamburger menu

### Floating elements
- [ ] Floating WhatsApp button (fixed bottom right)
- [ ] Optional floating “Request Quote” (bottom left)
- [ ] WhatsApp links: +92 333 8637777, +92 308 7959905

---

## 4. Homepage

### Hero
- [ ] H1: “Private Label Gym Gear & Sportswear Manufacturer”
- [ ] Include SEO keywords: B2B Gym Gear Manufacturer, Private Label Gym Gear Manufacturer, Sportswear Manufacturer Pakistan
- [ ] Button: Request a Quote → scroll to form
- [ ] Button: WhatsApp Us → open WhatsApp link

### Trust section
- [ ] Static content block for international exports

### What We Do (services preview)
- [ ] Reusable service component: icon, title, description
- [ ] Hover animation

### How It Works
- [ ] Step component: Share idea → Sample design → Approval → Production → Shipment

### Why Choose Us
- [ ] Feature grid with Lucide icons

### Call to action
- [ ] Big banner section with “GET QUOTATION” button

---

## 5. Products Page

### Database
- [ ] Categories table: id, name, slug, description, image, status
- [ ] Products table: id, category_id, name, slug, short_description, description, image, gallery (json), status
- [ ] Relationship: Category hasMany Products, Product belongsTo Category
- [ ] Seed categories: Gym Gear (sub-items: Weight Lifting Belts, Lever Belts, Wrist Wraps, Knee Sleeves, Elbow Sleeves, Lifting Straps, Gym Gloves, Resistance Bands, Barbell Pads, Ankle Straps, Gym Bags, Yoga Accessories), Sportswear (Gym T-Shirts, Tank Tops, Hoodies, Tracksuits, Shorts, Leggings, Compression Wear)

### Logic & UI
- [ ] Filter products by category
- [ ] Product listing page
- [ ] Product detail page
- [ ] Inquiry button on each product

---

## 6. Inquiry / Quotation System

### Database
- [ ] Table `inquiries`: id, name, company_name, email, whatsapp, country, product_interest, quantity, message, attachment, ip_address, created_at

### Form
- [ ] Fields: Full Name, Company Name, Email, WhatsApp Number, Country, Product Interest, Message, Estimated Quantity, File upload (logo)
- [ ] Validation via Laravel Request
- [ ] Store inquiry in DB
- [ ] Send email to info@zuaazgear.com
- [ ] Send auto-reply to client
- [ ] Redirect to thank-you page and show success message

### Auto-reply email
- [ ] Subject: “Thank you for contacting Atlantic garden Furniture”
- [ ] Content: We have received your inquiry. Our team will respond within 24 hours.
- [ ] Queue emails and retry failed sends

---

## 7. Admin Panel

### Auth & access
- [ ] Laravel Breeze or Sanctum for admin auth
- [ ] Admin-only role and route protection

### Features
- [ ] View inquiries list
- [ ] Export inquiries (CSV)
- [ ] Manage products (CRUD)
- [ ] Manage categories (CRUD)
- [ ] Manage FAQs
- [ ] Manage SEO settings
- [ ] Update homepage sections
- [ ] Update meta tags
- [ ] Admin notification email template

---

## 8. SEO

### Per page
- [ ] Custom meta title
- [ ] Meta description
- [ ] Meta keywords
- [ ] Open Graph tags
- [ ] Canonical URL
- [ ] Schema markup (Organization + FAQ where applicable)

### Homepage example
- [ ] Title: “Private Label Gym Gear Manufacturer | Sportswear Manufacturer Pakistan | Atlantic garden Furniture”
- [ ] Meta description includes: B2B Gym Gear Manufacturer, Private Label Fitness Brand Supplier, OEM Gym Equipment Manufacturer

### Technical
- [ ] Sitemap.xml auto-generated
- [ ] Robots.txt configured
- [ ] Structured data JSON-LD
- [ ] Image alt tags optimized

---

## 9. Quality Control Page

- [ ] Static page content: Pre-production sampling, In-process inspection, Final quality check, Packaging verification

---

## 10. Contact Page

- [ ] Display: Email (info@zuaazgear.com), Website (www.zuaazgear.com), WhatsApp numbers, Instagram link
- [ ] Corporate Office: Office 51, First Floor, Orchard Heights, Phase 1, Bahria Orchard, Lahore
- [ ] Manufacturing Location: Sialkot, Punjab
- [ ] Same form as quotation/inquiry form

---

## 11. Performance

- [ ] Lazy loading for images
- [ ] Laravel eager loading for relationships
- [ ] Optimized queries
- [ ] Minified CSS & JS (production)
- [ ] No blocking JS
- [ ] Target page load under 2 seconds

---

## 12. Security

- [ ] CSRF protection
- [ ] XSS protection
- [ ] File upload validation (type, size)
- [ ] Rate limiting on inquiry form
- [ ] Spam protection (honeypot or Google reCAPTCHA)
- [ ] Admin route protection

---

## 13. UI/UX Design

- [ ] Modern premium dark + white theme
- [ ] Fitness industrial look
- [ ] Smooth animations
- [ ] Soft shadows, rounded corners (2xl)
- [ ] Hover transitions
- [ ] Clean whitespace
- [ ] Strong typography hierarchy
- [ ] Open Sans (400, 600, 700)
- [ ] Lucide Icons only

---

## 14. Future Scalability (optional / ready)

- [ ] Multi-language ready (structure/keys)
- [ ] Blog module ready (optional)
- [ ] Catalog PDF download system
- [ ] Lead tracking analytics
- [ ] CRM integration ready (webhooks/events)

---

## 15. Email Configuration

- [ ] SMTP configurable via `.env`
- [ ] Queue emails
- [ ] Retry failed emails
- [ ] Admin notification template
- [ ] Client auto-reply template

---

## 16. Database Summary

### Tables
- [ ] users
- [ ] categories
- [ ] products
- [ ] inquiries
- [ ] faqs
- [ ] seo_settings
- [ ] homepage_sections

### Relationships
- [ ] Category hasMany Products
- [ ] Product belongsTo Category

---

## 17. Deliverables Checklist

- [ ] Full Laravel project structure
- [ ] Vue components modular structure
- [ ] Clean reusable components
- [ ] API routes (if SPA)
- [ ] Admin dashboard UI
- [ ] Seeders with dummy data
- [ ] SEO structured markup
- [ ] WhatsApp floating button
- [ ] Responsive Tailwind layout
- [ ] Production-ready build script

---

## Business objective (reminder)

**Goal:** Generate B2B inquiries.  
**Audience:** Amazon sellers, Shopify brand owners, gym brands, influencers, distributors.  
**Conversion focus:** Every page should push toward **Request a Quote** and **WhatsApp Contact**.
