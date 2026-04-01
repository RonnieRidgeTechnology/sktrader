<script setup>
import { inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Package, Palette, Ship } from 'lucide-vue-next';
import VectorCta from '@/Components/Vectors/VectorCta.vue';

const props = defineProps({
  headline: { type: String, default: 'Ready to find your perfect sofa?' },
  text: { type: String, default: 'Visit our showroom in Lusaka or get in touch — we will help you choose the right furniture for your home.' },
  buttonText: { type: String, default: 'CONTACT US' },
  buttonRoute: { type: String, default: 'contact' },
  hash: { type: String, default: '#quote' },
});

const openQuoteModal = inject('openQuoteModal', () => null);
const useQuoteModal = () => props.buttonRoute === 'contact' && props.hash === '#quote' && openQuoteModal;

const highlights = [
  { icon: Package, label: 'Quality sofas' },
  { icon: Palette, label: 'Showroom in Lusaka' },
  { icon: Ship, label: 'Nationwide delivery' },
];
</script>

<template>
  <section class="cta-banner relative min-h-[28rem] overflow-hidden border-t border-[#1c1917] bg-[#1c1917] px-4 py-14 sm:min-h-[32rem] sm:px-6 sm:py-24 lg:px-8 lg:py-32">
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
      <div class="cta-orb cta-orb-1 absolute -left-32 top-1/4 h-64 w-64 rounded-full bg-[#c2410c]/15 blur-3xl" />
      <div class="cta-orb cta-orb-2 absolute -right-32 bottom-1/4 h-80 w-80 rounded-full bg-[#c2410c]/10 blur-3xl" />
    </div>

    <div class="relative z-10 mx-auto grid min-w-0 max-w-7xl gap-10 sm:gap-12 lg:grid-cols-12 lg:gap-16 lg:items-center lg:gap-y-16">
      <div class="flex min-w-0 justify-center lg:col-span-5 xl:col-span-4">
        <div class="cta-vector w-full max-w-[220px] sm:max-w-[320px] text-[#f5f2ed]">
          <VectorCta />
        </div>
      </div>

      <div class="text-center lg:col-span-7 xl:col-span-8 lg:text-left">
        <h2 class="cta-headline font-editorial text-2xl font-bold tracking-tight text-[#f5f2ed] sm:text-3xl lg:text-4xl xl:text-5xl">
          {{ headline }}
        </h2>
        <p class="cta-text mt-4 max-w-xl text-base leading-relaxed text-[#f5f2ed]/80 sm:mt-5 sm:text-lg lg:mx-0 lg:text-xl">
          {{ text }}
        </p>

        <ul class="cta-highlights mt-8 flex flex-wrap justify-center gap-4 lg:justify-start" role="list">
          <li
            v-for="(item, i) in highlights"
            :key="item.label"
            class="flex items-center gap-2.5 border-2 border-white/20 bg-white/5 px-4 py-2.5"
            :style="{ animationDelay: (0.5 + i * 0.1) + 's' }"
          >
            <component :is="item.icon" class="h-5 w-5 shrink-0 text-[#c2410c]" stroke-width="2" />
            <span class="text-sm font-medium text-[#f5f2ed]">{{ item.label }}</span>
          </li>
        </ul>

        <div class="cta-button-wrap mt-8 sm:mt-10">
          <component
            :is="useQuoteModal() ? 'button' : Link"
            :type="useQuoteModal() ? 'button' : undefined"
            :href="useQuoteModal() ? undefined : route(buttonRoute) + hash"
            class="cta-button inline-flex min-h-[48px] min-w-0 items-center justify-center gap-2 border-2 border-[#c2410c] bg-[#c2410c] px-6 py-3.5 text-base font-semibold text-white transition hover:bg-[#a83609] hover:border-[#a83609] focus:outline-none focus:ring-2 focus:ring-[#c2410c] focus:ring-offset-2 focus:ring-offset-[#1c1917] sm:px-10 sm:py-4"
            @click="useQuoteModal() && openQuoteModal()"
          >
            {{ buttonText }}
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </component>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.cta-banner {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.cta-orb {
  animation: cta-float 8s ease-in-out infinite;
}
.cta-orb-2 {
  animation-delay: -3s;
}

.cta-vector {
  animation: cta-float-slow 6s ease-in-out infinite;
}

.cta-headline {
  animation: fade-in-up 0.7s ease-out 0.1s both;
}
.cta-text {
  animation: fade-in-up 0.7s ease-out 0.25s both;
}
.cta-highlights li {
  animation: fade-in-up 0.6s ease-out both;
}
.cta-button-wrap {
  animation: fade-in-up 0.7s ease-out 0.45s both;
}
.cta-button:hover {
  box-shadow: 0 0 32px -4px rgba(194, 65, 12, 0.35);
}
</style>
<style>
@keyframes cta-float {
  0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
  33% { transform: translate(20px, -15px) scale(1.05); opacity: 0.8; }
  66% { transform: translate(-10px, 10px) scale(0.98); opacity: 0.5; }
}
@keyframes cta-float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}
</style>
