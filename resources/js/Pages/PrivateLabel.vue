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

const props = defineProps({ title: { type: String, default: 'Premium Orders' }, pageContent: Object });
const pc = props.pageContent || {};

const pageSeoProps = usePageSeo(null, {
  title: 'Premium Orders & Gifting | SK Traders',
  description: 'Premium orders, gifting, and curated bundles — watches, perfumes, and skin care serums with a luxury experience.',
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
            <p class="luxe-kicker">{{ pc.hero_eyebrow || 'Premium orders' }}</p>
            <h1 class="luxe-title mt-4 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
              {{ pc.hero_title || 'Premium orders & gifting' }}
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-luxe-pearl/80">
              {{ pc.hero_subtitle || 'Need gift-ready picks, curated bundles, or premium selections for a special moment? We’ll help you choose the right watches, perfumes, and serums — fast.' }}
            </p>
          </div>
          <div class="flex justify-center lg:col-span-5">
            <div class="w-full max-w-[260px] text-white/15 lg:max-w-[320px]">
              <VectorPrivateLabel />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 1 -->
    <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
      <div class="luxe-container">
        <div class="luxe-surface rounded-3xl p-6 sm:p-8">
          <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
            <div class="order-2 lg:order-1 lg:col-span-5">
              <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                <VectorStepDesign />
              </div>
            </div>
            <div class="order-1 lg:order-2 lg:col-span-7">
              <p class="luxe-kicker">Step 1</p>
              <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                Tell us the vibe
              </h2>
              <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                Share your budget range, occasion, preferred scent profile (fresh, sweet, woody, spicy), and what you’re shopping for (watch / perfume / serum).
              </p>
              <ul class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Fast guidance via WhatsApp</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Curated picks tailored to you</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 2 -->
    <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
      <div class="luxe-container">
        <div class="luxe-surface rounded-3xl p-6 sm:p-8">
          <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
            <div class="lg:col-span-5">
              <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                <VectorStepTags />
              </div>
            </div>
            <div class="lg:col-span-7">
              <p class="luxe-kicker">Step 2</p>
              <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                Pick the set
              </h2>
              <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                Choose one premium hero item or a curated bundle. For perfumes, we’ll align the scent profile with the occasion. For serums, we’ll match routine goals.
              </p>
              <ul class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Gift-ready combinations</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Clear recommendations</span>
                </li>
              </ul>
            </div>
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
