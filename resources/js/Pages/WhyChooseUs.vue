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
  { key: 'sofas', label: 'Quality sofas', vector: VectorServiceMoq, body: 'A wide range of sofas and living room furniture. We choose durable materials and solid construction so your pieces last for years.', bullets: ['Fabric and leather options', 'Multiple sizes and styles'] },
  { key: 'showroom', label: 'Showroom in Lusaka', vector: VectorServices, body: 'Visit our showroom to try sofas and see furniture in person. Our team is on hand to help you find the right pieces for your home.', bullets: ['Try before you buy', 'Friendly advice'] },
  { key: 'delivery', label: 'Nationwide delivery', vector: VectorWorldwideShipping, body: 'We deliver across Zambia. Whether you are in Lusaka or elsewhere, we can get your furniture to you safely.', bullets: ['Delivery across Zambia', 'Safe and reliable'] },
  { key: 'value', label: 'Fair pricing', vector: VectorStepTags, body: 'Transparent prices with no hidden costs. We offer value for money so you can furnish your space without compromise.', bullets: ['Clear pricing', 'No hidden fees'] },
  { key: 'style', label: 'Style for every home', vector: VectorStepDesign, body: 'From classic to modern — find the look that suits your space. We stock a variety of styles to match different tastes.', bullets: ['Classic and modern', 'Wide selection'] },
  { key: 'warranty', label: 'Warranty & support', vector: VectorGlobeTrust, body: 'We stand by our quality with warranty on our furniture. If you need help after your purchase, we are here.', bullets: ['Warranty on products', 'After-sales support'] },
  { key: 'quality', label: 'Quality materials', vector: VectorQuality, body: 'We use durable fabrics and solid frames. Every piece is built to last and look good in your home for years.', bullets: ['Durable construction', 'Quality fabrics'] },
  { key: 'service', label: 'Friendly service', vector: VectorWhyChoose, body: 'A team that cares about helping you find the right furniture. We are here to answer questions and make your experience smooth.', bullets: ['Helpful staff', 'Easy to work with'] },
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
  title: 'Why Choose Us | Atlantic Garden Furniture',
  description: 'Quality sofas and furniture, showroom in Lusaka, nationwide delivery, warranty and friendly service. Atlantic Garden Furniture Zambia.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-[#1c1917] bg-[#0f0e0d] px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
      <div class="relative mx-auto max-w-7xl">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-center lg:gap-12">
          <div class="lg:col-span-7">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#f5f2ed]/60">{{ pc.hero_eyebrow || 'Your partner in growth' }}</p>
            <h1 class="mt-2 font-editorial text-3xl font-bold tracking-tight text-[#f5f2ed] sm:text-4xl">
              {{ pc.hero_title || 'Why Choose Us' }}
            </h1>
            <p class="mt-3 max-w-2xl text-lg leading-relaxed text-[#f5f2ed]/80">
              {{ pc.hero_subtitle || 'We combine quality materials, a showroom in Lusaka and nationwide delivery with friendly service. One place for sofas and furniture in Zambia.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <div class="w-full max-w-[220px] text-[#f5f2ed]/40 lg:max-w-[260px]">
              <VectorWhyChoose />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section
      v-for="(step, index) in steps"
      :key="step.key"
      class="relative border-b border-editorial-ink/10 px-4 py-8 sm:px-6 lg:px-8 lg:py-10"
      :class="index % 2 === 0 ? 'bg-editorial-cream' : 'bg-editorial-paper'"
    >
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-center lg:gap-10">
          <div
            class="order-2 flex justify-center lg:col-span-5"
            :class="index % 2 === 0 ? 'lg:order-1' : 'lg:order-2'"
          >
            <div class="h-[140px] w-full max-w-[200px] text-editorial-slate/50 sm:h-[160px] sm:max-w-[240px] lg:max-w-none lg:h-[180px]">
              <component :is="step.vector" class="h-full w-full" />
            </div>
          </div>
          <div
            class="order-1 lg:col-span-7"
            :class="index % 2 === 0 ? 'lg:order-2' : 'lg:order-1'"
          >
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">{{ index + 1 }}</span>
            <h2 class="mt-1.5 font-editorial text-xl font-bold tracking-tight text-editorial-ink sm:text-2xl">
              {{ step.label }}
            </h2>
            <p class="mt-3 text-editorial-slate leading-relaxed text-sm sm:text-base">
              {{ step.body }}
            </p>
            <ul v-if="step.bullets?.length" class="mt-3 space-y-1.5 text-sm text-editorial-slate">
              <li v-for="(bullet, i) in step.bullets" :key="i" class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
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
