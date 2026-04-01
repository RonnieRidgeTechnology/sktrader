<script setup>
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import VectorPrivateLabel from '@/Components/Vectors/VectorPrivateLabel.vue';
import VectorStepDesign from '@/Components/Vectors/VectorStepDesign.vue';
import VectorStepTags from '@/Components/Vectors/VectorStepTags.vue';
import VectorStepPackaging from '@/Components/Vectors/VectorStepPackaging.vue';
import VectorStepPrint from '@/Components/Vectors/VectorStepPrint.vue';
import VectorStepQuality from '@/Components/Vectors/VectorStepQuality.vue';

const props = defineProps({ title: { type: String, default: 'Custom Furniture' }, pageContent: Object });
const pc = props.pageContent || {};

const pageSeoProps = usePageSeo(null, {
  title: 'Custom Furniture | SK Traders',
  description: 'Custom and made-to-order furniture in Zambia. SK Traders can help with bespoke sofas and pieces for your space.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="relative overflow-hidden border-b border-[#1c1917] bg-[#0f0e0d] px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
      <div class="relative mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-7">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral text-[#f5f2ed]/60">Made for you</p>
            <h1 class="mt-3 font-editorial text-3xl font-bold tracking-tight text-[#f5f2ed] sm:text-4xl lg:text-5xl">
              {{ pc.hero_title || 'Custom Furniture' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-[#f5f2ed]/80">
              {{ pc.hero_subtitle || 'Looking for a specific size, fabric or design? We offer custom and made-to-order options for sofas and furniture. Get in touch to discuss your requirements.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <div class="w-full max-w-[260px] text-[#f5f2ed]/40 lg:max-w-[320px]">
              <VectorPrivateLabel />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 1: Design & Artwork -->
    <section class="relative border-b border-editorial-ink/10 bg-editorial-cream px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="order-2 lg:order-1 lg:col-span-5">
            <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-editorial-slate/70">
              <VectorStepDesign />
            </div>
          </div>
          <div class="order-1 lg:order-2 lg:col-span-7">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">Step 1</span>
            <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">
              Your design &amp; artwork
            </h2>
            <p class="mt-4 text-editorial-slate leading-relaxed">
              Send us your logo, artwork, or ideas. We work in common formats (AI, PDF, PNG, high-res JPG) and can adapt designs for print and embroidery. If you don’t have final artwork yet, our team can help refine or create it so it looks sharp on fabric and labels.
            </p>
            <ul class="mt-5 space-y-2 text-sm text-editorial-slate">
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Logo and brand assets in print-ready format</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Optional design support and adjustments</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 2: Tags & Labels -->
    <section class="relative border-b border-editorial-ink/10 bg-editorial-paper px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-5">
            <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-editorial-slate/70">
              <VectorStepTags />
            </div>
          </div>
          <div class="lg:col-span-7">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">Step 2</span>
            <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">
              Private-label tags &amp; labels
            </h2>
            <p class="mt-4 text-editorial-slate leading-relaxed">
              Your brand name and logo go on hang tags, care labels, size labels, and more. We source or produce custom tags so every piece that leaves our facility carries your branding. Options include different materials, shapes, and finishes to match your brand.
            </p>
            <ul class="mt-5 space-y-2 text-sm text-editorial-slate">
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Custom hang tags with your logo and product info</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Care and content labels tailored to your product</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 3: Custom packaging -->
    <section class="relative border-b border-editorial-ink/10 bg-editorial-cream px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="order-2 lg:order-1 lg:col-span-5">
            <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-editorial-slate/70">
              <VectorStepPackaging />
            </div>
          </div>
          <div class="order-1 lg:order-2 lg:col-span-7">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">Step 3</span>
            <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">
              Custom packaging
            </h2>
            <p class="mt-4 text-editorial-slate leading-relaxed">
              Packaging is part of the brand experience. We offer branded boxes, poly bags, and other packaging options so your products look professional when they reach your customers. You can keep it simple or go for a more premium unboxing feel — we work with you on materials and design.
            </p>
            <ul class="mt-5 space-y-2 text-sm text-editorial-slate">
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Branded boxes and mailers</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Poly bags and protective packaging with your logo</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 4: Printing & embroidery -->
    <section class="relative border-b border-editorial-ink/10 bg-editorial-paper px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="lg:col-span-5">
            <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-editorial-slate/70">
              <VectorStepPrint />
            </div>
          </div>
          <div class="lg:col-span-7">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">Step 4</span>
            <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">
              Printing &amp; embroidery
            </h2>
            <p class="mt-4 text-editorial-slate leading-relaxed">
              Your logo and graphics go onto the product itself through screen printing, DTF (direct-to-film), or embroidery. We help you choose the right method for your design and fabric, and we keep quality consistent so every piece looks the same. Low minimums mean you can start small and scale as your brand grows.
            </p>
            <ul class="mt-5 space-y-2 text-sm text-editorial-slate">
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Screen print, DTF, and embroidery options</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>Low MOQ so startups and new brands can order with confidence</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 5: Quality & delivery -->
    <section class="relative border-b border-editorial-ink/10 bg-editorial-cream px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
      <div class="mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
          <div class="order-2 lg:order-1 lg:col-span-5">
            <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-editorial-slate/70">
              <VectorStepQuality />
            </div>
          </div>
          <div class="order-1 lg:order-2 lg:col-span-7">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-editorial-coral">Step 5</span>
            <h2 class="mt-2 font-editorial text-2xl font-bold tracking-tight text-editorial-ink sm:text-3xl">
              Quality check &amp; worldwide delivery
            </h2>
            <p class="mt-4 text-editorial-slate leading-relaxed">
              Before shipment, we run quality checks so your branded products meet your standards. Once approved, we ship worldwide — to the USA, UK, Europe, Canada, Australia, GCC, and more. You get a single partner for manufacturing, branding, and logistics.
            </p>
            <ul class="mt-5 space-y-2 text-sm text-editorial-slate">
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>In-process and final QC before dispatch</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-editorial-ink" aria-hidden="true" />
                <span>International shipping to your door</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </AppLayout>
</template>
