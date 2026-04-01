<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ settings: Object });

const activeTab = ref('general');

const tabs = [
  { id: 'general', label: 'General' },
  { id: 'contact', label: 'Contact & Address' },
  { id: 'branding', label: 'Branding' },
  { id: 'site', label: 'Site & CTAs' },
  { id: 'seo', label: 'SEO' },
  { id: 'email', label: 'Email (SMTP)' },
  { id: 'payments', label: 'Payments' },
];

const form = useForm({
  _method: 'PUT',
  site_name: props.settings?.site_name ?? '',
  tagline: props.settings?.tagline ?? '',
  header_logo: null,
  footer_logo: null,
  favicon: null,
  primary_color: props.settings?.primary_color ?? '',
  secondary_color: props.settings?.secondary_color ?? '',
  contact_phone: props.settings?.contact_phone ?? '',
  contact_email: props.settings?.contact_email ?? '',
  contact_website: props.settings?.contact_website ?? '',
  contact_instagram: props.settings?.contact_instagram ?? '',
  contact_facebook: props.settings?.contact_facebook ?? '',
  contact_linkedin: props.settings?.contact_linkedin ?? '',
  contact_twitter: props.settings?.contact_twitter ?? '',
  address_office: props.settings?.address_office ?? '',
  address_manufacturing: props.settings?.address_manufacturing ?? '',
  whatsapp_primary: props.settings?.whatsapp_primary ?? '',
  whatsapp_secondary: props.settings?.whatsapp_secondary ?? '',
  whatsapp_enabled: props.settings?.whatsapp_enabled !== '0',
  map_embed_code: props.settings?.map_embed_code ?? '',
  smtp_host: props.settings?.smtp_host ?? '',
  smtp_port: props.settings?.smtp_port ?? '587',
  smtp_username: props.settings?.smtp_username ?? '',
  smtp_password: props.settings?.smtp_password ?? '',
  smtp_encryption: props.settings?.smtp_encryption ?? 'tls',
  smtp_from_address: props.settings?.smtp_from_address ?? '',
  smtp_from_name: props.settings?.smtp_from_name ?? '',
  maintenance_mode: props.settings?.maintenance_mode === true || props.settings?.maintenance_mode === '1',
  footer_copyright: props.settings?.footer_copyright ?? '',
  google_analytics_id: props.settings?.google_analytics_id ?? '',
  custom_head_scripts: props.settings?.custom_head_scripts ?? '',
  hero_cta_primary: props.settings?.hero_cta_primary ?? '',
  hero_cta_secondary: props.settings?.hero_cta_secondary ?? '',
  contact_form_title: props.settings?.contact_form_title ?? '',
  newsletter_enabled: props.settings?.newsletter_enabled !== false && props.settings?.newsletter_enabled !== '0',
  meta_title_default: props.settings?.meta_title_default ?? '',
  meta_description_default: props.settings?.meta_description_default ?? '',
  og_image: null,
  zynlepay_merchant_id: props.settings?.zynlepay_merchant_id ?? '',
  zynlepay_api_id: props.settings?.zynlepay_api_id ?? '',
  zynlepay_api_key: props.settings?.zynlepay_api_key ?? '',
  zynlepay_channel: props.settings?.zynlepay_channel ?? 'momo',
  zynlepay_service_id: props.settings?.zynlepay_service_id ?? '1002',
  zynlepay_sandbox: props.settings?.zynlepay_sandbox === true || props.settings?.zynlepay_sandbox === '1',
  paypal_enabled: props.settings?.paypal_enabled === true || props.settings?.paypal_enabled === '1',
  paypal_sandbox: props.settings?.paypal_sandbox === true || props.settings?.paypal_sandbox === '1',
  paypal_client_id: props.settings?.paypal_client_id ?? '',
  paypal_client_secret: props.settings?.paypal_client_secret ?? '',
  paypal_currency: props.settings?.paypal_currency ?? '',
});

const testEmail = useForm({ test_email: '' });

function submit() {
  form
    .transform((data) => ({
      ...data,
      // Multipart + checkboxes: always send explicit 0/1 so Laravel saves toggles reliably
      whatsapp_enabled: data.whatsapp_enabled ? '1' : '0',
      maintenance_mode: data.maintenance_mode ? '1' : '0',
      newsletter_enabled: data.newsletter_enabled ? '1' : '0',
      zynlepay_sandbox: data.zynlepay_sandbox ? '1' : '0',
      paypal_enabled: data.paypal_enabled ? '1' : '0',
      paypal_sandbox: data.paypal_sandbox ? '1' : '0',
    }))
    .post(route('admin.settings.update'), { forceFormData: true });
}

function sendTestEmail() {
  if (!testEmail.test_email) return;
  testEmail.post(route('admin.settings.test-smtp'));
}

const inputClass =
  'mt-1 block w-full rounded-xl border border-zinc-300 px-3 py-2 shadow-sm transition focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:focus:border-amber-400 dark:focus:ring-amber-400';
const labelClass = 'block text-sm font-medium text-zinc-700 dark:text-zinc-300';
const sectionClass =
  'rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-[0_1px_3px_rgba(0,0,0,0.04)] dark:border-zinc-700/80 dark:bg-zinc-800/50';
</script>

<template>
  <AdminLayout>
    <div class="mx-auto max-w-4xl">
      <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Settings</h1>
      <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
        Control site-wide options, contact info, branding, CTAs, and more. Changes apply to the public site immediately after save.
      </p>

      <!-- Tabs -->
      <div class="mt-8 border-b border-zinc-200 dark:border-zinc-700">
        <nav class="-mb-px flex gap-1 overflow-x-auto pb-px" aria-label="Settings sections">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            :class="[
              'whitespace-nowrap rounded-t-xl border-b-2 px-4 py-3 text-sm font-medium transition',
              activeTab === tab.id
                ? 'border-amber-500 text-amber-600 dark:border-amber-400 dark:text-amber-400'
                : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-300',
            ]"
            @click="activeTab = tab.id"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <form @submit.prevent="submit" class="mt-6">
        <!-- Tab: General -->
        <div v-show="activeTab === 'general'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">General</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Site name</label>
                <input v-model="form.site_name" type="text" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">Tagline</label>
                <input v-model="form.tagline" type="text" :class="inputClass" />
              </div>
            </div>
          </section>
        </div>

        <!-- Tab: Contact & Address -->
        <div v-show="activeTab === 'contact'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Contact (shown on Contact page)</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Phone</label>
                <input v-model="form.contact_phone" type="text" :class="inputClass" placeholder="e.g. +92 333 1234567" />
              </div>
              <div>
                <label :class="labelClass">Email</label>
                <input v-model="form.contact_email" type="email" :class="inputClass" placeholder="info@example.com" />
              </div>
              <div class="sm:col-span-2">
                <label :class="labelClass">Website</label>
                <input v-model="form.contact_website" type="text" :class="inputClass" placeholder="www.example.com" />
              </div>
            </div>
            <p class="mt-6 text-sm font-medium text-zinc-700 dark:text-zinc-300">Social media (shown in footer left)</p>
            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Add full URLs. Only platforms with a URL will appear in the footer.</p>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Instagram URL</label>
                <input v-model="form.contact_instagram" type="url" :class="inputClass" placeholder="https://instagram.com/yourpage" />
              </div>
              <div>
                <label :class="labelClass">Facebook URL</label>
                <input v-model="form.contact_facebook" type="url" :class="inputClass" placeholder="https://facebook.com/yourpage" />
              </div>
              <div>
                <label :class="labelClass">LinkedIn URL</label>
                <input v-model="form.contact_linkedin" type="url" :class="inputClass" placeholder="https://linkedin.com/company/yourpage" />
              </div>
              <div>
                <label :class="labelClass">X (Twitter) URL</label>
                <input v-model="form.contact_twitter" type="url" :class="inputClass" placeholder="https://x.com/yourpage" />
              </div>
            </div>
          </section>

          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Address (shown on Contact page)</h2>
            <div class="mt-4 space-y-4">
              <div>
                <label :class="labelClass">Office address</label>
                <textarea v-model="form.address_office" rows="2" :class="inputClass" placeholder="Street, city, country" />
              </div>
              <div>
                <label :class="labelClass">Manufacturing address</label>
                <input v-model="form.address_manufacturing" type="text" :class="inputClass" placeholder="e.g. Sialkot, Punjab" />
              </div>
            </div>
          </section>

          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">WhatsApp</h2>
            <div class="mt-4 space-y-4">
              <label class="flex items-center gap-2">
                <input v-model="form.whatsapp_enabled" type="checkbox" class="rounded border-zinc-300" />
                <span class="text-sm text-zinc-700 dark:text-zinc-300">Show floating WhatsApp button</span>
              </label>
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label :class="labelClass">Primary number</label>
                  <input v-model="form.whatsapp_primary" type="text" :class="inputClass" placeholder="+923001234567" />
                </div>
                <div>
                  <label :class="labelClass">Secondary number</label>
                  <input v-model="form.whatsapp_secondary" type="text" :class="inputClass" />
                </div>
              </div>
            </div>
          </section>

          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Map embed</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Paste iframe embed code from Google Maps (e.g. for Contact page).</p>
            <textarea v-model="form.map_embed_code" rows="4" placeholder='<iframe src="..." ...></iframe>' :class="[inputClass, 'mt-3 font-mono text-sm']" />
          </section>
        </div>

        <!-- Tab: Branding -->
        <div v-show="activeTab === 'branding'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Branding</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Header logo</label>
                <input type="file" accept="image/*" class="mt-1 block w-full text-sm" @change="form.header_logo = $event.target.files?.[0]" />
                <img v-if="settings?.header_logo_url" :src="settings.header_logo_url" alt="Header logo" class="mt-2 h-10 object-contain" />
              </div>
              <div>
                <label :class="labelClass">Footer logo</label>
                <input type="file" accept="image/*" class="mt-1 block w-full text-sm" @change="form.footer_logo = $event.target.files?.[0]" />
                <img v-if="settings?.footer_logo_url" :src="settings.footer_logo_url" alt="Footer logo" class="mt-2 h-10 object-contain" />
              </div>
              <div>
                <label :class="labelClass">Favicon</label>
                <input type="file" accept="image/png,image/x-icon,.ico" class="mt-1 block w-full text-sm" @change="form.favicon = $event.target.files?.[0]" />
                <img v-if="settings?.favicon_url" :src="settings.favicon_url" alt="Favicon" class="mt-2 h-8 w-8 object-contain" />
              </div>
              <div class="space-y-2">
                <label :class="labelClass">Primary color (hex)</label>
                <div class="flex gap-2">
                  <input v-model="form.primary_color" type="text" placeholder="#f59e0b" :class="inputClass" />
                  <input v-if="form.primary_color" type="color" :value="form.primary_color.replace(/^#?([a-f0-9]{6})$/i, '#$1')" class="h-10 w-14 cursor-pointer rounded border border-zinc-300 dark:border-zinc-600" @input="form.primary_color = $event.target.value" />
                </div>
              </div>
              <div class="space-y-2">
                <label :class="labelClass">Secondary color (hex)</label>
                <div class="flex gap-2">
                  <input v-model="form.secondary_color" type="text" placeholder="#ea580c" :class="inputClass" />
                  <input v-if="form.secondary_color" type="color" :value="form.secondary_color.replace(/^#?([a-f0-9]{6})$/i, '#$1')" class="h-10 w-14 cursor-pointer rounded border border-zinc-300 dark:border-zinc-600" @input="form.secondary_color = $event.target.value" />
                </div>
              </div>
            </div>
          </section>
        </div>

        <!-- Tab: Site & CTAs -->
        <div v-show="activeTab === 'site'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Maintenance & visibility</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">When maintenance mode is on, visitors see a maintenance message (admin stays accessible).</p>
            <label class="mt-4 flex cursor-pointer items-center gap-3">
              <input v-model="form.maintenance_mode" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800" />
              <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Enable maintenance mode</span>
            </label>
          </section>
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Footer</h2>
            <div class="mt-4">
              <label :class="labelClass">Copyright text (e.g. © 2025 Your Company)</label>
              <input v-model="form.footer_copyright" type="text" :class="inputClass" placeholder="© 2025 SK Traders" />
            </div>
          </section>
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Homepage hero CTAs</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Button and link text on the main hero section.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Primary CTA (main button)</label>
                <input v-model="form.hero_cta_primary" type="text" :class="inputClass" placeholder="e.g. View Products" />
              </div>
              <div>
                <label :class="labelClass">Secondary CTA (link)</label>
                <input v-model="form.hero_cta_secondary" type="text" :class="inputClass" placeholder="e.g. Contact Us" />
              </div>
            </div>
          </section>
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Contact & newsletter</h2>
            <div class="mt-4 space-y-4">
              <div>
                <label :class="labelClass">Contact form heading (override)</label>
                <input v-model="form.contact_form_title" type="text" :class="inputClass" placeholder="e.g. Get in touch" />
              </div>
              <label class="flex cursor-pointer items-center gap-3">
                <input v-model="form.newsletter_enabled" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800" />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Show newsletter signup (if your theme supports it)</span>
              </label>
            </div>
          </section>
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Analytics & custom code</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Google Analytics (GA4) and optional scripts in &lt;head&gt;. Use with care.</p>
            <div class="mt-4 space-y-4">
              <div>
                <label :class="labelClass">Google Analytics ID (e.g. G-XXXXXXXXXX)</label>
                <input v-model="form.google_analytics_id" type="text" :class="inputClass" placeholder="G-XXXXXXXXXX" />
              </div>
              <div>
                <label :class="labelClass">Custom &lt;head&gt; scripts (HTML, injected before &lt;/head&gt;)</label>
                <textarea v-model="form.custom_head_scripts" rows="6" :class="[inputClass, 'font-mono text-sm']" placeholder="&lt;script&gt;...&lt;/script&gt;" />
              </div>
            </div>
          </section>
        </div>

        <!-- Tab: SEO (global meta fallbacks) -->
        <div v-show="activeTab === 'seo'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Global meta (fallbacks)</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Used when a page does not set its own meta title, description, or og:image. These become the default meta tags for that page.
            </p>
            <div class="mt-4 space-y-4">
              <div>
                <label :class="labelClass">Global meta title</label>
                <input v-model="form.meta_title_default" type="text" :class="inputClass" placeholder="e.g. Watches, Perfumes & Serums | SK Traders" maxlength="70" />
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Recommended under 60 characters for search results.</p>
              </div>
              <div>
                <label :class="labelClass">Global meta description</label>
                <textarea v-model="form.meta_description_default" rows="3" :class="inputClass" placeholder="Short default description for search engines and social sharing" maxlength="320" />
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Recommended 150–160 characters.</p>
              </div>
              <div>
                <label :class="labelClass">Default OG image (Open Graph / social sharing)</label>
                <input type="file" accept="image/png,image/jpeg,image/jpg,image/webp" class="mt-1 block w-full text-sm" @change="form.og_image = $event.target.files?.[0]" />
                <img v-if="settings?.og_image_url || form.og_image" :src="form.og_image ? URL.createObjectURL(form.og_image) : settings.og_image_url" alt="OG preview" class="mt-2 max-h-24 rounded border border-zinc-200 object-contain dark:border-zinc-600" />
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Shown when a page has no specific og:image. Recommended 1200×630 px.</p>
              </div>
            </div>
          </section>
        </div>

        <!-- Tab: Payments -->
        <div v-show="activeTab === 'payments'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">PayPal</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Enable PayPal checkout. Create a REST API app in the
              <a href="https://developer.paypal.com/dashboard/" target="_blank" rel="noopener" class="text-amber-600 hover:underline dark:text-amber-400">PayPal Developer Dashboard</a>
              (Sandbox for testing, Live for production). You must save <strong>both</strong> Client ID and Secret — checkout only shows PayPal when both are stored (paste the Secret on first save; it is never shown again). Return URL is handled automatically (
              <code class="rounded bg-zinc-200 px-1 dark:bg-zinc-700">/checkout/paypal/return</code>
              with your site domain).
            </p>
            <div class="mt-4 space-y-4">
              <label class="flex cursor-pointer items-center gap-3">
                <input v-model="form.paypal_enabled" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800" />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Enable PayPal on checkout</span>
              </label>
              <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                  <label :class="labelClass">Client ID</label>
                  <input v-model="form.paypal_client_id" type="text" :class="inputClass" placeholder="PayPal REST API Client ID" autocomplete="off" />
                </div>
                <div class="sm:col-span-2">
                  <label :class="labelClass">Secret</label>
                  <input v-model="form.paypal_client_secret" type="password" :class="inputClass" placeholder="Leave blank to keep current secret" autocomplete="new-password" />
                </div>
                <div>
                  <label :class="labelClass">Charge currency (ISO 4217)</label>
                  <input v-model="form.paypal_currency" type="text" :class="inputClass" placeholder="e.g. ZMW, USD (empty = order currency)" maxlength="3" autocomplete="off" />
                  <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">PayPal must support this currency for your account. Leave empty to use the order currency.</p>
                </div>
                <div class="sm:col-span-2">
                  <label class="flex cursor-pointer items-center gap-3">
                    <input v-model="form.paypal_sandbox" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800" />
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Use PayPal Sandbox (testing)</span>
                  </label>
                </div>
              </div>
            </div>
          </section>

          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">ZynlePay (online payments)</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Configure ZynlePay so customers can pay online (Mobile Money / card). Leave blank to disable. Get credentials from <a href="https://sandbox.zynlepay.com" target="_blank" rel="noopener" class="text-amber-600 hover:underline dark:text-amber-400">ZynlePay Sandbox</a> or production. In your ZynlePay dashboard, set the webhook URL to your full site URL + <code class="rounded bg-zinc-200 px-1 dark:bg-zinc-700">/zynlepay/webhook</code> (e.g. <code class="rounded bg-zinc-200 px-1 dark:bg-zinc-700">https://yoursite.com/zynlepay/webhook</code>).
            </p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Merchant ID</label>
                <input v-model="form.zynlepay_merchant_id" type="text" :class="inputClass" placeholder="Your ZynlePay merchant ID" autocomplete="off" />
              </div>
              <div>
                <label :class="labelClass">API ID</label>
                <input v-model="form.zynlepay_api_id" type="text" :class="inputClass" placeholder="API ID" autocomplete="off" />
              </div>
              <div class="sm:col-span-2">
                <label :class="labelClass">API Key</label>
                <input v-model="form.zynlepay_api_key" type="password" :class="inputClass" placeholder="API key (leave blank to keep current)" autocomplete="new-password" />
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Enter a new value to change; submitting the form will overwrite the stored key.</p>
              </div>
              <div>
                <label :class="labelClass">Channel</label>
                <select v-model="form.zynlepay_channel" :class="inputClass">
                  <option value="momo">Mobile Money (MOMO)</option>
                  <option value="card">Card</option>
                  <option value="bank">Bank</option>
                </select>
              </div>
              <div>
                <label :class="labelClass">Service ID</label>
                <input v-model="form.zynlepay_service_id" type="text" :class="inputClass" placeholder="1002" />
              </div>
              <div class="sm:col-span-2">
                <label class="flex cursor-pointer items-center gap-3">
                  <input v-model="form.zynlepay_sandbox" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800" />
                  <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Use sandbox (testing)</span>
                </label>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Enable for testing; disable for live payments.</p>
              </div>
            </div>
          </section>
        </div>

        <!-- Tab: Email (SMTP) -->
        <div v-show="activeTab === 'email'" class="space-y-6">
          <section :class="sectionClass">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">SMTP (outgoing email)</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Used for inquiry notifications and test emails. Leave blank to use .env mail config.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <label :class="labelClass">Host</label>
                <input v-model="form.smtp_host" type="text" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">Port</label>
                <input v-model="form.smtp_port" type="text" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">Username</label>
                <input v-model="form.smtp_username" type="text" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">Password</label>
                <input v-model="form.smtp_password" type="password" autocomplete="new-password" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">Encryption</label>
                <select v-model="form.smtp_encryption" :class="inputClass">
                  <option value="">None</option>
                  <option value="tls">TLS</option>
                  <option value="ssl">SSL</option>
                </select>
              </div>
              <div>
                <label :class="labelClass">From address</label>
                <input v-model="form.smtp_from_address" type="email" :class="inputClass" />
              </div>
              <div>
                <label :class="labelClass">From name</label>
                <input v-model="form.smtp_from_name" type="text" :class="inputClass" />
              </div>
            </div>
            <div class="mt-6 flex flex-wrap items-end gap-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-900/50">
              <div class="min-w-[200px]">
                <label :class="labelClass">Send test email to</label>
                <input v-model="testEmail.test_email" type="email" placeholder="email@example.com" :class="inputClass" />
              </div>
              <button type="button" :disabled="testEmail.processing || !testEmail.test_email" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-amber-400 disabled:opacity-50" @click="sendTestEmail">
                {{ testEmail.processing ? 'Sendingâ€¦' : 'Test SMTP' }}
              </button>
            </div>
          </section>
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-3">
          <button type="submit" :disabled="form.processing" class="rounded-2xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
            {{ form.processing ? 'Savingâ€¦' : 'Save settings' }}
          </button>
          <p class="text-xs text-zinc-500 dark:text-zinc-400">Settings are cached; the public site may take a few seconds to reflect changes.</p>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
