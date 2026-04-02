<script setup>
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InquiryForm from '@/Components/InquiryForm.vue';
import { Mail, Phone, Globe, MessageCircle, Building2, Factory } from 'lucide-vue-next';

const props = defineProps({ title: String, initialProductInterest: String, pageContent: Object });
const page = usePage();
const zuaaz = page.props.zuaaz || {};
const pc = props.pageContent || {};
const whatsappLink = () => {
  const num = (zuaaz.whatsapp?.primary || '+260970000000').replace(/\D/g, '');
  return `https://wa.me/${num}`;
};
const whatsappSecondaryLink = () => {
  const num = (zuaaz.whatsapp?.secondary || '').replace(/\D/g, '');
  return num ? `https://wa.me/${num}` : '';
};

const pageSeoProps = usePageSeo(null, {
  title: 'Contact Us | SK Traders',
  description: 'Contact SK Traders for watches, perfumes and skincare serums. Send an enquiry or chat via WhatsApp.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <!-- Hero – luxe -->
    <section class="relative min-w-0 overflow-hidden border-b border-white/10 bg-luxe-obsidian px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
      <div class="relative mx-auto min-w-0 max-w-4xl text-center">
        <p class="luxe-kicker">{{ pc.hero_eyebrow || 'Get in touch' }}</p>
        <h1 class="luxe-title mt-4 text-3xl sm:text-4xl lg:text-5xl">
          {{ zuaaz.contact_form_title || pc.hero_title || 'Contact Us' }}
        </h1>
        <p class="mx-auto mt-5 max-w-xl text-base text-luxe-pearl/80 sm:text-lg">
          {{ pc.hero_subtitle || 'Need help choosing a watch, fragrance, or serum? Message us — we respond quickly.' }}
        </p>
      </div>
    </section>

    <!-- Main content – luxe -->
    <section class="min-w-0 bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-20">
      <div class="mx-auto min-w-0 max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-5 lg:gap-14">
          <div class="min-w-0 lg:col-span-2">
            <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">{{ pc.contact_info_heading || 'Contact information' }}</h2>
            <div class="mt-6 space-y-6">
              <a
                :href="`mailto:${zuaaz.contact?.email || 'info@sktraders.com'}`"
                class="luxe-surface rounded-3xl p-5 flex gap-4 transition hover:bg-white/10"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                  <Mail class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-[0.25em] text-luxe-mist">Email</p>
                  <p class="mt-1 break-words text-sm font-medium text-luxe-pearl">{{ zuaaz.contact?.email || 'info@sktraders.com' }}</p>
                </div>
              </a>
              <a
                v-if="zuaaz.contact?.phone"
                :href="`tel:${zuaaz.contact.phone.replace(/\s/g, '')}`"
                class="luxe-surface rounded-3xl p-5 flex gap-4 transition hover:bg-white/10"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                  <Phone class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-[0.25em] text-luxe-mist">Phone</p>
                  <p class="mt-1 text-sm font-medium text-luxe-pearl">{{ zuaaz.contact.phone }}</p>
                </div>
              </a>
              <div class="luxe-surface rounded-3xl p-5 flex gap-4 transition hover:bg-white/10">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                  <MessageCircle class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-[0.25em] text-luxe-mist">WhatsApp</p>
                  <p class="mt-1 space-y-0.5 text-sm font-medium text-luxe-pearl">
                    <a :href="whatsappLink()" target="_blank" rel="noopener noreferrer" class="block text-luxe-gold underline-offset-2 hover:underline">{{ zuaaz.whatsapp?.primary || '+260 97 000 0000' }}</a>
                    <a v-if="zuaaz.whatsapp?.secondary" :href="whatsappSecondaryLink()" target="_blank" rel="noopener noreferrer" class="block text-luxe-gold underline-offset-2 hover:underline">{{ zuaaz.whatsapp.secondary }}</a>
                  </p>
                </div>
              </div>
              <a
                v-if="zuaaz.contact?.website"
                :href="zuaaz.contact.website"
                target="_blank"
                rel="noopener noreferrer"
                class="luxe-surface rounded-3xl p-5 flex gap-4 transition hover:bg-white/10"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                  <Globe class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-[0.25em] text-luxe-mist">Website</p>
                  <p class="mt-1 truncate text-sm font-medium text-luxe-pearl">{{ zuaaz.contact.website }}</p>
                </div>
              </a>
              <a
                v-if="zuaaz.contact?.instagram"
                :href="zuaaz.contact.instagram"
                target="_blank"
                rel="noopener noreferrer"
                class="luxe-surface rounded-3xl p-5 flex gap-4 transition hover:bg-white/10"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                  <Globe class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-[0.25em] text-luxe-mist">Instagram</p>
                  <p class="mt-1 text-sm font-medium text-luxe-pearl">Follow us</p>
                </div>
              </a>
            </div>

            <h2 class="mt-10 text-sm font-semibold uppercase tracking-[0.2em] text-[#44403c]">{{ pc.visit_heading || 'Reach us' }}</h2>
            <div class="mt-4 space-y-4">
              <div class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Building2 class="h-5 w-5" stroke-width="2" />
                </span>
                <div>
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Office</p>
                  <p class="mt-1 text-sm text-[#44403c]">{{ zuaaz.address?.office || 'Lusaka, Zambia' }}</p>
                </div>
              </div>
              <div v-if="zuaaz.address?.manufacturing" class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Factory class="h-5 w-5" stroke-width="2" />
                </span>
                <div>
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Delivery</p>
                  <p class="mt-1 text-sm text-[#44403c]">{{ zuaaz.address.manufacturing }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="lg:col-span-3">
            <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-gold">{{ pc.quote_heading || 'Send an enquiry' }}</h2>
            <p class="mt-3 font-display text-xl font-semibold text-luxe-pearl">{{ pc.form_intro_heading || "Need help choosing the right product? We're here." }}</p>
            <p class="mt-2 text-luxe-pearl/80">
              {{ pc.quote_subtitle || "Share what you're looking for and we’ll respond with availability, pricing, and next steps." }}
            </p>
            <div class="mt-6">
              <InquiryForm :initial-product-interest="initialProductInterest" />
            </div>
          </div>
        </div>

        <div v-if="zuaaz.map_embed_code" class="mt-14">
          <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#44403c]">{{ pc.find_heading || 'Find us' }}</h2>
          <div class="mt-4 overflow-hidden border-2 border-[#1c1917]/10 bg-white">
            <div class="aspect-video w-full [&>iframe]:h-full [&>iframe]:w-full" v-html="zuaaz.map_embed_code" />
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
