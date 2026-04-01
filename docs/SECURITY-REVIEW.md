# Security Review (Phase 5.7)

## CSRF
- **Laravel:** All state-changing requests use CSRF token via `@csrf` / Axios/Inertia (Breeze/Inertia sends token in header or body).
- **Inquiry form:** Submitted via Inertia `form.post()` which includes CSRF token.

## XSS
- **Blade:** `{{ }}` escapes output; `{!! !!}` only used for JSON-LD (structured data from config).
- **Vue/Inertia:** Templates use `{{ }}` (escaped). No `v-html` on user/admin content.
- **Admin:** Product/category/FAQ content displayed as text; no raw HTML from DB rendered.

## File uploads
- **Inquiry attachment:** `StoreInquiryRequest`: `mimes:pdf,png,jpg,jpeg,ai,eps`, `max:5120` (5MB).
- **Admin categories:** `image|mimes:jpeg,png,jpg,webp|max:5120`.
- **Admin products:** `image|mimes:jpeg,png,jpg,webp|max:5120` for image and `gallery.*`.

## Rate limiting
- **Inquiry:** `throttle:5,1` (5 attempts per minute) on `POST /inquiry`.

## Spam protection
- **Honeypot:** Inquiry form has hidden `website` field with `max:0` validation (must be empty).

## Admin
- **Auth:** Admin routes use `auth` + `admin` middleware (`EnsureUserIsAdmin` checks `user->is_admin`).
- **Non-admin users** hitting `/admin` are redirected to dashboard.

## Recommendations
- Keep `APP_DEBUG=false` in production.
- Use HTTPS and set `SESSION_SECURE_COOKIE=true` in production.
- Ensure `APP_KEY` is set and never committed.
