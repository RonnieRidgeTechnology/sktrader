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
  { key: 'sofas', vector: VectorServiceMoq, label: 'Collection 1', title: 'Sofas & seating', body: 'A wide range of sofas — 2-seater, 3-seater, L-shaped and corner sofas in fabric and leather. Find the right size and style for your living room.', bullets: ['Fabric and leather options', 'Multiple sizes and styles'] },
  { key: 'living', vector: VectorStepPrint, label: 'Collection 2', title: 'Living room', body: 'Armchairs, coffee tables, TV units and bookshelves to complete your lounge. Quality pieces that last.', bullets: ['Armchairs and occasional chairs', 'Tables and storage'] },
  { key: 'bedroom', vector: VectorStepTags, label: 'Collection 3', title: 'Bedroom', body: 'Beds, wardrobes, bedside and dressing tables. Create a comfortable and organised bedroom.', bullets: ['Beds and headboards', 'Wardrobes and storage'] },
  { key: 'dining', vector: VectorStepPackaging, label: 'Collection 4', title: 'Dining', body: 'Dining tables, chairs and sets for family meals and entertaining. Sturdy and stylish.', bullets: ['Tables and chairs', 'Dining sets'] },
  { key: 'quality', vector: VectorStepDesign, label: 'Collection 5', title: 'Quality materials', body: 'We use durable fabrics and solid frames so your furniture stands up to daily use and keeps its look for years.', bullets: ['Solid construction', 'Quality fabrics'] },
  { key: 'delivery', vector: VectorServiceSampling, label: 'Collection 6', title: 'Showroom & delivery', body: 'Visit our showroom in Lusaka to try before you buy, or order from home. We deliver across Zambia.', bullets: ['Lusaka showroom', 'Nationwide delivery'] },
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
  title: 'Our Collections | Atlantic Garden Furniture',
  description: 'Browse our furniture collections: sofas, living room, bedroom and office furniture. Quality pieces for every home in Zambia.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-[#1c1917] bg-[#0f0e0d] px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
      <div class="relative mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-7">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">What we offer</p>
            <h1 class="mt-3 font-editorial text-3xl font-bold tracking-tight text-[#f5f2ed] sm:text-4xl lg:text-5xl">
              {{ pc.hero_title || 'Our Collections' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-[#f5f2ed]/80">
              {{ pc.hero_subtitle || 'Sofas, living room furniture and home pieces to suit every style. Visit our showroom in Lusaka or browse online — we deliver across Zambia.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <VectorServices class="h-64 w-64 text-[#f5f2ed]/30 sm:h-80 sm:w-80" />
          </div>
        </div>
      </div>
    </section>

    <section class="min-w-0 bg-editorial-cream px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-24">
      <div class="mx-auto max-w-7xl">
        <div class="space-y-16 sm:space-y-20">
          <div
            v-for="(step, index) in steps"
            :key="step.key"
            class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16"
            :class="index % 2 === 1 ? 'lg:flex-row-reverse' : ''"
          >
            <div class="min-w-0 lg:col-span-7" :class="index % 2 === 1 ? 'lg:order-2' : ''">
              <span class="text-xs font-semibold uppercase tracking-wider text-editorial-coral">{{ step.label }}</span>
              <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">{{ step.title }}</h2>
              <p class="mt-4 text-base leading-relaxed text-editorial-slate">{{ step.body }}</p>
              <ul v-if="step.bullets && step.bullets.length" class="mt-4 list-inside list-disc space-y-2 text-sm text-editorial-slate">
                <li v-for="(b, i) in step.bullets" :key="i">{{ b }}</li>
              </ul>
            </div>
            <div class="flex justify-center lg:col-span-5" :class="index % 2 === 1 ? 'lg:order-1' : ''">
              <component :is="step.vector" class="h-56 w-56 text-editorial-slate/40 sm:h-64 sm:w-64" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </AppLayout>
</template>
