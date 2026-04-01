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

    <!-- Step 3 -->
    <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
      <div class="luxe-container">
        <div class="luxe-surface rounded-3xl p-6 sm:p-8">
          <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
            <div class="order-2 lg:order-1 lg:col-span-5">
              <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                <VectorStepPackaging />
              </div>
            </div>
            <div class="order-1 lg:order-2 lg:col-span-7">
              <p class="luxe-kicker">Step 3</p>
              <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                Make it gift-ready
              </h2>
              <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                Presentation matters. We’ll help you choose premium picks that feel elevated at first glance — perfect for gifting, celebrations, and special moments.
              </p>
              <ul class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Premium selection and presentation</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Bundles that feel intentional</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 4 -->
    <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
      <div class="luxe-container">
        <div class="luxe-surface rounded-3xl p-6 sm:p-8">
          <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
            <div class="lg:col-span-5">
              <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                <VectorStepPrint />
              </div>
            </div>
            <div class="lg:col-span-7">
              <p class="luxe-kicker">Step 4</p>
              <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                Confirm details fast
              </h2>
              <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                We’ll confirm what matters: availability, pricing, and the exact items in your set. If you’re gifting, we’ll help you pick the best match for the occasion.
              </p>
              <ul class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Clear recommendations</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Quick WhatsApp confirmation</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Step 5 -->
    <section class="bg-luxe-obsidian px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
      <div class="luxe-container">
        <div class="luxe-surface rounded-3xl p-6 sm:p-8">
          <div class="grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-16">
            <div class="order-2 lg:order-1 lg:col-span-5">
              <div class="aspect-[4/3] w-full max-w-sm mx-auto lg:max-w-none text-white/15">
                <VectorStepQuality />
              </div>
            </div>
            <div class="order-1 lg:order-2 lg:col-span-7">
              <p class="luxe-kicker">Step 5</p>
              <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                Pack & deliver
              </h2>
              <p class="mt-4 text-luxe-pearl/80 leading-relaxed">
                Once confirmed, we pack carefully and dispatch. Need an update? Message us anytime — we’ll keep it simple and fast.
              </p>
              <ul class="mt-5 grid gap-2 text-sm text-luxe-pearl/80 sm:grid-cols-2">
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Careful packing</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>Support for delivery updates</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </AppLayout>
</template>
