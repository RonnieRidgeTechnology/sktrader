<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { MapPin } from 'lucide-vue-next';

const props = defineProps({
  regions: {
    type: Array,
    default: () => [
      { name: 'Lusaka' },
      { name: 'Copperbelt' },
      { name: 'Southern' },
      { name: 'Central' },
      { name: 'Northern' },
      { name: 'Nationwide' },
    ],
  },
  section: { type: Object, default: null },
});
const eyebrow = computed(() => props.section?.eyebrow || 'We deliver across Zambia');
const headline = computed(() => props.section?.headline || 'From our showroom in Lusaka to your home — nationwide.');
const footer = computed(() => props.section?.footer || ' for delivery details and lead times.');
</script>

<template>
  <section class="delivery-line relative overflow-hidden border-t border-[#1c1917]/10 px-4 py-16 sm:px-6 sm:py-20 md:px-8 lg:px-12" aria-label="Delivery">
    <!-- Background: gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#faf8f5] via-[#f5f2ed] to-[#efeae4]" aria-hidden="true" />

    <!-- Explanatory vectors: Zambia delivery concept -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
      <!-- Simplified Zambia outline with Lusaka hub and delivery routes to regions -->
      <div class="delivery-map absolute h-full w-full max-h-[420px] max-w-[420px] text-[#1c1917]/[0.08] sm:max-h-[500px] sm:max-w-[500px]">
        <svg viewBox="0 0 200 240" fill="none" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <!-- Simplified Zambia shape (stylized outline) -->
          <path
            d="M100 20 L160 45 L175 90 L165 140 L140 180 L100 220 L60 180 L35 140 L25 90 L40 45 Z"
            stroke="currentColor"
            stroke-width="1.2"
            fill="none"
          />
          <!-- Lusaka (hub) - center -->
          <circle cx="100" cy="115" r="6" fill="currentColor" />
          <circle cx="100" cy="115" r="3" fill="#f5f2ed" />
          <!-- Delivery routes (dotted lines from Lusaka to regions) -->
          <line x1="100" y1="115" x2="145" y2="95" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <line x1="100" y1="115" x2="155" y2="130" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <line x1="100" y1="115" x2="125" y2="195" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <line x1="100" y1="115" x2="75" y2="195" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <line x1="100" y1="115" x2="55" y2="130" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <line x1="100" y1="115" x2="55" y2="95" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 3" />
          <!-- Destination nodes (regional delivery points) -->
          <circle cx="145" cy="95" r="3" fill="currentColor" />
          <circle cx="155" cy="130" r="3" fill="currentColor" />
          <circle cx="125" cy="195" r="3" fill="currentColor" />
          <circle cx="75" cy="195" r="3" fill="currentColor" />
          <circle cx="55" cy="130" r="3" fill="currentColor" />
          <circle cx="55" cy="95" r="3" fill="currentColor" />
        </svg>
      </div>
    </div>

    <!-- Left: Delivery truck vector (explanatory - we ship) -->
    <div class="delivery-truck-left absolute left-4 top-1/2 hidden -translate-y-1/2 text-[#1c1917]/[0.09] md:block" style="width: 160px;" aria-hidden="true">
      <svg viewBox="0 0 80 50" fill="none" stroke="currentColor" stroke-width="1" class="h-full w-full">
        <path d="M5 35 L5 42 Q5 47 12 47 L25 47 L28 35 L55 35 L58 47 L68 47 Q75 47 75 42 L75 35" />
        <path d="M28 35 L28 15 L55 15 L55 35" />
        <path d="M55 25 L72 25 L75 30 L75 35" />
        <circle cx="18" cy="47" r="4" fill="none" stroke="currentColor" stroke-width="1" />
        <circle cx="62" cy="47" r="4" fill="none" stroke="currentColor" stroke-width="1" />
        <path d="M12 22 L20 22 M45 22 L52 22" />
      </svg>
      <p class="mt-1 text-center text-[10px] font-medium uppercase tracking-wider opacity-80">Delivery</p>
    </div>

    <!-- Right: Package / door-to-door concept -->
    <div class="delivery-door absolute right-4 top-1/2 hidden -translate-y-1/2 text-[#1c1917]/[0.09] md:block" style="width: 140px;" aria-hidden="true">
      <svg viewBox="0 0 60 70" fill="none" stroke="currentColor" stroke-width="1" class="h-full w-full">
        <!-- House / destination -->
        <path d="M30 8 L55 28 L55 62 L5 62 L5 28 Z" />
        <path d="M30 28 L30 62" />
        <path d="M18 45 L42 45" />
        <!-- Package -->
        <rect x="22" y="35" width="16" height="14" rx="1" />
        <path d="M30 35 L30 49 M22 42 L38 42" />
      </svg>
      <p class="mt-1 text-center text-[10px] font-medium uppercase tracking-wider opacity-80">Your home</p>
    </div>

    <div class="relative mx-auto max-w-4xl text-center">
      <div class="inline-flex items-center justify-center gap-2 rounded-none border border-[#c2410c]/20 bg-white/80 px-4 py-2 shadow-sm">
        <MapPin class="h-5 w-5 text-[#c2410c]" stroke-width="2" aria-hidden="true" />
        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-[#c2410c]">{{ eyebrow }}</span>
      </div>
      <p class="font-editorial mt-5 text-2xl font-semibold leading-snug text-[#1c1917] sm:text-3xl">
        {{ headline }}
      </p>
      <p class="mt-3 text-sm text-[#44403c]">
        Lusaka as our hub — we deliver to all major regions across the country.
      </p>
      <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <span
          v-for="r in regions"
          :key="r.name"
          class="rounded-none border border-[#1c1917]/20 bg-white px-4 py-2.5 text-sm font-medium text-[#1c1917] shadow-[0_1px_2px_0_rgba(28,25,23,0.05)] transition hover:border-[#c2410c]/40 hover:shadow-md"
        >
          {{ r.name }}
        </span>
      </div>
      <p class="mt-8 text-sm text-[#44403c]">
        <Link :href="route('contact')" class="font-medium text-[#1c1917] underline-offset-4 hover:underline">
          Contact us
        </Link>
        {{ footer }}
      </p>
    </div>
  </section>
</template>

<style scoped>
.delivery-line {
  isolation: isolate;
}
.delivery-map {
  margin: 0 auto;
}
@media (min-width: 768px) {
  .delivery-truck-left {
    left: 8%;
  }
  .delivery-door {
    right: 8%;
  }
}
@media (min-width: 1024px) {
  .delivery-truck-left {
    left: 12%;
  }
  .delivery-door {
    right: 12%;
  }
}
</style>
