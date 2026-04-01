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
  title: 'Contact Us | Atlantic Garden Furniture',
  description: 'Contact Atlantic Garden Furniture for sofas and furniture in Zambia. Visit our Lusaka showroom or send an enquiry.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <!-- Hero – concept dark -->
    <section class="relative min-w-0 overflow-hidden border-b border-[#1c1917] bg-[#0f0e0d] px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
      <div class="relative mx-auto min-w-0 max-w-4xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">{{ pc.hero_eyebrow || 'Get in touch' }}</p>
        <h1 class="mt-3 font-editorial text-2xl font-bold tracking-tight text-[#f5f2ed] sm:text-3xl lg:text-4xl">
          {{ zuaaz.contact_form_title || pc.hero_title || 'Contact Us' }}
        </h1>
        <p class="mx-auto mt-4 max-w-xl text-lg text-[#f5f2ed]/80">
          {{ pc.hero_subtitle || 'Visit our showroom, call us, or send a message. We respond within 24 hours.' }}
        </p>
      </div>
    </section>

    <!-- Main content – cream -->
    <section class="min-w-0 bg-editorial-cream px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-20">
      <div class="mx-auto min-w-0 max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-5 lg:gap-14">
          <div class="min-w-0 lg:col-span-2">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#44403c]">{{ pc.contact_info_heading || 'Contact information' }}</h2>
            <div class="mt-6 space-y-6">
              <a
                :href="`mailto:${zuaaz.contact?.email || 'info@atlanticgardenfurniture.com'}`"
                class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5 transition hover:border-[#c2410c]/30"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Mail class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Email</p>
                  <p class="mt-1 truncate text-sm font-medium text-[#1c1917]">{{ zuaaz.contact?.email || 'info@atlanticgardenfurniture.com' }}</p>
                </div>
              </a>
              <a
                v-if="zuaaz.contact?.phone"
                :href="`tel:${zuaaz.contact.phone.replace(/\s/g, '')}`"
                class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5 transition hover:border-[#c2410c]/30"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Phone class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Phone</p>
                  <p class="mt-1 text-sm font-medium text-editorial-ink">{{ zuaaz.contact.phone }}</p>
                </div>
              </a>
              <div class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5 transition hover:border-[#c2410c]/30">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <MessageCircle class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">WhatsApp</p>
                  <p class="mt-1 space-y-0.5 text-sm font-medium text-[#1c1917]">
                    <a :href="whatsappLink()" target="_blank" rel="noopener noreferrer" class="block text-[#c2410c] underline-offset-2 hover:underline">{{ zuaaz.whatsapp?.primary || '+260 97 000 0000' }}</a>
                    <a v-if="zuaaz.whatsapp?.secondary" :href="whatsappSecondaryLink()" target="_blank" rel="noopener noreferrer" class="block text-[#c2410c] underline-offset-2 hover:underline">{{ zuaaz.whatsapp.secondary }}</a>
                  </p>
                </div>
              </div>
              <a
                v-if="zuaaz.contact?.website"
                :href="zuaaz.contact.website"
                target="_blank"
                rel="noopener noreferrer"
                class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5 transition hover:border-[#c2410c]/30"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Globe class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Website</p>
                  <p class="mt-1 truncate text-sm font-medium text-[#1c1917]">{{ zuaaz.contact.website }}</p>
                </div>
              </a>
              <a
                v-if="zuaaz.contact?.instagram"
                :href="zuaaz.contact.instagram"
                target="_blank"
                rel="noopener noreferrer"
                class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5 transition hover:border-[#c2410c]/30"
              >
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Globe class="h-5 w-5" stroke-width="2" />
                </span>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Instagram</p>
                  <p class="mt-1 text-sm font-medium text-[#1c1917]">Follow us</p>
                </div>
              </a>
            </div>

            <h2 class="mt-10 text-sm font-semibold uppercase tracking-[0.2em] text-[#44403c]">{{ pc.visit_heading || 'Visit us' }}</h2>
            <div class="mt-4 space-y-4">
              <div class="flex gap-4 border-2 border-[#1c1917]/10 bg-white p-5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#c2410c] text-white">
                  <Building2 class="h-5 w-5" stroke-width="2" />
                </span>
                <div>
                  <p class="text-xs font-medium uppercase tracking-wider text-[#44403c]">Showroom</p>
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
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#c2410c]">{{ pc.quote_heading || 'Send an enquiry' }}</h2>
            <p class="mt-3 font-editorial text-lg font-semibold text-[#1c1917]">{{ pc.form_intro_heading || "Looking for sofas or furniture? We're here to help." }}</p>
            <p class="mt-2 text-[#44403c]">
              {{ pc.quote_subtitle || "Share your requirements and we’ll respond within 24 hours with pricing and next steps." }}
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
