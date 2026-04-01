<script setup>
import { computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import VectorQuality from '@/Components/Vectors/VectorQuality.vue';
import VectorQualitySample from '@/Components/Vectors/VectorQualitySample.vue';
import VectorQualityProcess from '@/Components/Vectors/VectorQualityProcess.vue';
import VectorQualityFinal from '@/Components/Vectors/VectorQualityFinal.vue';
import VectorQualityPack from '@/Components/Vectors/VectorQualityPack.vue';

const props = defineProps({ title: { type: String, default: 'Quality Control' }, pageContent: Object });
const pc = props.pageContent || {};
const defaultSteps = [
  {
    key: 'intro',
    vector: VectorQuality,
    label: 'Overview',
    title: 'Quality at every stage',
    body: 'We maintain quality from sampling to shipment. Each order goes through defined checks so your products meet your specifications and our standards. Here’s how we do it.',
    bullets: ['Pre-production sampling', 'In-process and final inspection', 'Packaging verification'],
  },
  {
    key: 'sample',
    vector: VectorQualitySample,
    label: 'Step 1',
    title: 'Pre-production sampling',
    body: 'We produce samples for your approval before bulk production. This ensures design, materials, fit, and finish meet your expectations. You sign off on the sample so we can replicate it at scale.',
    bullets: ['Samples in your chosen materials and colors', 'Approval before bulk run'],
  },
  {
    key: 'process',
    vector: VectorQualityProcess,
    label: 'Step 2',
    title: 'In-process inspection',
    body: 'During production we carry out in-process checks to maintain consistency and catch any issues early. This keeps quality steady and avoids surprises at the final stage.',
    bullets: ['Checks at key production stages', 'Early correction of deviations'],
  },
  {
    key: 'final',
    vector: VectorQualityFinal,
    label: 'Step 3',
    title: 'Final quality check',
    body: 'Before packing, every order goes through a final quality check against your specifications and our standards. We verify construction, branding, and finish so only approved pieces are shipped.',
    bullets: ['Inspection against your specs', 'Consistent standards every time'],
  },
  {
    key: 'pack',
    vector: VectorQualityPack,
    label: 'Step 4',
    title: 'Packaging verification',
    body: 'We verify packaging, labelling, and carton quality so your products reach your customers in perfect condition. Correct labels and secure packing are part of our quality commitment.',
    bullets: ['Labels and packaging checked', 'Secure and correct shipment'],
  },
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
  title: 'Our Quality | SK Traders',
  description: 'Quality-first selection and careful handling — curated watches, perfumes, and serums with premium presentation.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-white/10 bg-luxe-obsidian">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
      <div class="luxe-container relative py-16 sm:py-20">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-7">
            <p class="luxe-kicker">{{ pc.hero_eyebrow || 'Quality-first' }}</p>
            <h1 class="luxe-title mt-4 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
              {{ pc.hero_title || 'Our quality' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-luxe-pearl/80">
              {{ pc.hero_subtitle || 'From selection to packing, we focus on quality and consistency so every order feels premium.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <div class="w-full max-w-[260px] text-white/15 lg:max-w-[300px]">
              <VectorQuality />
            </div>
          </div>
        </div>
      </div>
    </section>

    <template v-for="(step, index) in steps" :key="step.key">
      <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
        <div class="luxe-container">
          <div class="luxe-surface rounded-3xl p-6 sm:p-8">
            <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
              <div class="order-2 lg:col-span-5" :class="index % 2 === 1 ? 'lg:order-2' : 'lg:order-1'">
                <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                  <component :is="step.vector" />
                </div>
              </div>
              <div class="order-1 lg:col-span-7" :class="index % 2 === 1 ? 'lg:order-1' : 'lg:order-2'">
                <p class="luxe-kicker">{{ step.label }}</p>
                <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                  {{ step.title }}
                </h2>
                <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                  {{ step.body }}
                </p>
                <ul v-if="step.bullets?.length" class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                  <li v-for="(bullet, bi) in step.bullets" :key="bi" class="flex items-start gap-2">
                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                    <span>{{ bullet }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
    </template>

    <CtaBanner />
  </AppLayout>
</template>
