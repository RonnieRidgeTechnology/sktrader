<script setup>
import { computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import VectorServices from '@/Components/Vectors/VectorServices.vue';
import VectorServiceMoq from '@/Components/Vectors/VectorServiceMoq.vue';
import VectorStepPrint from '@/Components/Vectors/VectorStepPrint.vue';
import VectorStepTags from '@/Components/Vectors/VectorStepTags.vue';
import VectorStepPackaging from '@/Components/Vectors/VectorStepPackaging.vue';
import VectorStepDesign from '@/Components/Vectors/VectorStepDesign.vue';
import VectorServiceSampling from '@/Components/Vectors/VectorServiceSampling.vue';
import VectorWorldwideShipping from '@/Components/Vectors/VectorWorldwideShipping.vue';

const props = defineProps({ title: String, pageContent: Object });
const pc = props.pageContent || {};
const defaultSteps = [
  { key: 'watches', vector: VectorServiceMoq, label: 'Collection 1', title: 'Watches', body: 'Timepieces with refined materials, clean dials, and confident silhouettes — curated for daily wear and special occasions.', bullets: ['New drops & limited pieces', 'Curated finishes and styles'] },
  { key: 'perfumes', vector: VectorStepPrint, label: 'Collection 2', title: 'Perfumes', body: 'Signature scents with lasting presence — from fresh and bright to deep, warm, and smoky.', bullets: ['Everyday and evening profiles', 'Gift-ready selections'] },
  { key: 'serums', vector: VectorStepTags, label: 'Collection 3', title: 'Skin care serums', body: 'Targeted serums designed to support your routine — hydration, glow, and clarity-focused picks.', bullets: ['Routine-friendly options', 'Curated for results-focused care'] },
  { key: 'quality', vector: VectorStepDesign, label: 'Collection 4', title: 'Quality-first curation', body: 'We choose products with quality, presentation, and reliability in mind — so you can shop with confidence.', bullets: ['Selection-first approach', 'Consistent standards'] },
  { key: 'delivery', vector: VectorWorldwideShipping, label: 'Collection 5', title: 'Delivery & support', body: 'Simple ordering, careful packing, and support when you need it — including WhatsApp concierge help.', bullets: ['Careful packing', 'Fast support via WhatsApp'] },
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
  title: 'Collections | SK Traders',
  description: 'Explore SK Traders collections: watches, perfumes, and skin care serums — curated luxury essentials with premium presentation.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-white/10 bg-luxe-obsidian">
      <div class="luxe-container py-16 sm:py-20">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-7">
            <p class="luxe-kicker">{{ pc.hero_eyebrow || 'Explore' }}</p>
            <h1 class="luxe-title mt-4 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
              {{ pc.hero_title || 'Collections' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-luxe-pearl/80">
              {{ pc.hero_subtitle || 'Watches, perfumes, and skin care serums — curated luxury essentials, delivered with care.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <VectorServices class="h-64 w-64 text-white/15 sm:h-80 sm:w-80" />
          </div>
        </div>
      </div>
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
    </section>

    <section class="bg-luxe-obsidian">
      <div class="luxe-container py-12 sm:py-16 lg:py-24">
        <div class="space-y-6 sm:space-y-8">
          <div
            v-for="(step, index) in steps"
            :key="step.key"
            class="luxe-surface rounded-3xl p-6 sm:p-8"
          >
            <div class="grid gap-8 lg:grid-cols-12 lg:items-center lg:gap-12">
              <div class="lg:col-span-7" :class="index % 2 === 1 ? 'lg:order-2' : 'lg:order-1'">
                <p class="luxe-kicker">{{ step.label }}</p>
                <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">{{ step.title }}</h2>
                <p class="mt-4 text-base leading-relaxed text-luxe-pearl/80">{{ step.body }}</p>
                <ul v-if="step.bullets?.length" class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                  <li v-for="(b, i) in step.bullets" :key="i" class="flex items-start gap-2">
                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                    <span>{{ b }}</span>
                  </li>
                </ul>
              </div>
              <div class="flex justify-center lg:col-span-5" :class="index % 2 === 1 ? 'lg:order-1' : 'lg:order-2'">
                <component :is="step.vector" class="h-56 w-56 text-white/15 sm:h-64 sm:w-64" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </AppLayout>
</template>
