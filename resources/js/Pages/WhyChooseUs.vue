<script setup>
import { computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import VectorWhyChoose from '@/Components/Vectors/VectorWhyChoose.vue';
import VectorServiceMoq from '@/Components/Vectors/VectorServiceMoq.vue';
import VectorServices from '@/Components/Vectors/VectorServices.vue';
import VectorWorldwideShipping from '@/Components/Vectors/VectorWorldwideShipping.vue';
import VectorStepTags from '@/Components/Vectors/VectorStepTags.vue';
import VectorStepDesign from '@/Components/Vectors/VectorStepDesign.vue';
import VectorQuality from '@/Components/Vectors/VectorQuality.vue';
import VectorGlobeTrust from '@/Components/Vectors/VectorGlobeTrust.vue';

const props = defineProps({ title: { type: String, default: 'Why Choose Us' }, pageContent: Object });
const pc = props.pageContent || {};
const defaultSteps = [
  { key: 'curation', label: 'Curation', vector: VectorServiceMoq, body: 'A focused selection across watches, perfumes, and serums — chosen for quality, presentation, and everyday usability.', bullets: ['Curated drops', 'Premium presentation'] },
  { key: 'auth', label: 'Confidence', vector: VectorServices, body: 'Clear product details and a quality-first approach so you can shop with confidence and minimal guesswork.', bullets: ['Clear specs', 'Transparent experience'] },
  { key: 'delivery', label: 'Delivery', vector: VectorWorldwideShipping, body: 'Careful packing and reliable delivery flow — built to keep products protected from checkout to unboxing.', bullets: ['Careful packing', 'Delivery support'] },
  { key: 'support', label: 'Concierge support', vector: VectorGlobeTrust, body: 'Quick help before and after purchase via WhatsApp — sizes, scent profiles, routine tips, and order updates.', bullets: ['WhatsApp concierge', 'Fast answers'] },
  { key: 'gifting', label: 'Gift-ready', vector: VectorStepTags, body: 'Polished, premium storefront experience that makes gifting easy — with products that look as good as they feel.', bullets: ['Premium feel', 'Easy gifting'] },
  { key: 'quality', label: 'Quality checks', vector: VectorQuality, body: 'We prioritize products that meet consistent standards — so each order feels intentional, not random.', bullets: ['Consistent standards', 'Selection-first'] },
  { key: 'design', label: 'Modern experience', vector: VectorStepDesign, body: 'A modern, cinematic storefront built for discovery — faster browsing, clearer decisions, better conversion.', bullets: ['Better discovery', 'Cleaner UI'] },
  { key: 'service', label: 'Service', vector: VectorWhyChoose, body: 'A team that cares about the details — from product questions to delivery follow-ups.', bullets: ['Friendly service', 'Smooth experience'] },
];
const steps = computed(() => {
  const fromContent = pc.steps || [];
  return defaultSteps.map((s, i) => {
    const o = fromContent[i];
    if (!o) return s;
    return {
      ...s,
      label: o.label || s.label,
      title: o.title || s.title,
      body: o.body || s.body,
      bullets: Array.isArray(o.bullets) ? o.bullets : (o.bullets ? String(o.bullets).split('\n').filter(Boolean) : s.bullets),
    };
  });
});

const pageSeoProps = usePageSeo(null, {
  title: 'Why Choose Us | SK Traders',
  description: 'Curated watches, perfumes, and serums — premium presentation, careful packing, and concierge support. Shop SK Traders.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-white/10 bg-luxe-obsidian">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
      <div class="luxe-container relative py-12 sm:py-16">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-center lg:gap-12">
          <div class="lg:col-span-7">
            <p class="luxe-kicker">{{ pc.hero_eyebrow || 'Why SK Traders' }}</p>
            <h1 class="luxe-title mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">
              {{ pc.hero_title || 'Why choose us' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-luxe-pearl/80">
              {{ pc.hero_subtitle || 'Curated luxury essentials with careful packing, modern browsing, and concierge-style support.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <div class="w-full max-w-[220px] text-white/15 lg:max-w-[260px]">
              <VectorWhyChoose />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-luxe-obsidian">
      <div class="luxe-container py-12 sm:py-16 lg:py-20">
        <div class="grid gap-6 sm:gap-8 lg:grid-cols-2">
          <div
            v-for="(step, index) in steps"
            :key="step.key"
            class="luxe-surface rounded-3xl p-6 sm:p-8"
          >
            <div class="flex items-start justify-between gap-6">
              <div class="min-w-0">
                <p class="luxe-kicker">Reason {{ String(index + 1).padStart(2, '0') }}</p>
                <h2 class="luxe-title mt-3 text-xl font-semibold tracking-tight sm:text-2xl">{{ step.label }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-luxe-pearl/80 sm:text-base">{{ step.body }}</p>
              </div>
              <div class="shrink-0 text-white/15">
                <component :is="step.vector" class="h-16 w-16 sm:h-20 sm:w-20" />
              </div>
            </div>
            <ul v-if="step.bullets?.length" class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
              <li v-for="(bullet, i) in step.bullets" :key="i" class="flex items-start gap-2">
                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                <span>{{ bullet }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </AppLayout>
</template>
