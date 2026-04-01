<script setup>
import { computed } from 'vue';
import { Package, MapPin, Truck } from 'lucide-vue-next';

const defaultPillars = [
  { icon: Package, title: 'Quality first', text: 'Durable materials and solid construction. Sofas and furniture built to last in your home.' },
  { icon: MapPin, title: 'Showroom in Lusaka', text: 'Try before you buy. Visit us to see and feel the pieces in person.' },
  { icon: Truck, title: 'Nationwide delivery', text: 'We deliver across Zambia. From Lusaka to your door, we get it there safely.' },
];
const icons = [Package, MapPin, Truck];
const props = defineProps({ section: { type: Object, default: null } });
const heading = computed(() => props.section?.heading || 'Quality sofas, showroom in Lusaka, nationwide delivery.');
const pillars = computed(() => {
  const raw = props.section?.pillars;
  if (!Array.isArray(raw) || raw.length < 3) return defaultPillars;
  return raw.slice(0, 3).map((p, i) => ({
    icon: icons[i] || Package,
    title: p?.title || defaultPillars[i]?.title || '',
    text: p?.text || defaultPillars[i]?.text || '',
  }));
});
</script>

<template>
  <section class="why-pillars relative overflow-hidden px-4 py-20 sm:px-6 sm:py-28 md:px-8 lg:px-12" aria-label="Why us">
    <!-- Elegant background: gradient + furniture vectors -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#faf8f5] via-[#f5f2ed] to-[#efeae4]" aria-hidden="true" />
    <div class="absolute inset-0 opacity-[0.035]" aria-hidden="true">
      <div class="absolute -left-[10%] top-[5%] h-[280px] w-[280px] text-[#1c1917]">
        <!-- Sofa silhouette -->
        <svg viewBox="0 0 120 60" fill="none" stroke="currentColor" stroke-width="0.8" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <path d="M10 35 L10 50 Q10 55 15 55 L105 55 Q110 55 110 50 L110 35 Q110 28 100 25 L85 22 L75 20 L45 20 L35 22 L20 25 Q10 28 10 35Z" />
          <path d="M15 25 L25 18 L40 15 L80 15 L95 18 L105 25" />
        </svg>
      </div>
      <div class="absolute right-[0%] top-[15%] h-[200px] w-[200px] rotate-12 text-[#1c1917]">
        <!-- Armchair -->
        <svg viewBox="0 0 80 90" fill="none" stroke="currentColor" stroke-width="0.8" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <path d="M15 70 L15 82 Q15 88 22 88 L58 88 Q65 88 65 82 L65 70 Q65 62 55 58 L45 55 L35 58 Q25 62 25 70Z" />
          <path d="M25 58 L30 35 L35 20 Q40 12 40 8 L40 5" />
          <path d="M55 58 L50 35 L45 20 Q40 12 40 8" />
          <path d="M20 35 L60 35 L58 45 L22 45 Z" />
        </svg>
      </div>
      <div class="absolute bottom-[8%] left-[15%] h-[160px] w-[160px] -rotate-6 text-[#1c1917]">
        <!-- Side table / console -->
        <svg viewBox="0 0 100 70" fill="none" stroke="currentColor" stroke-width="0.8" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <rect x="10" y="25" width="80" height="8" rx="1" />
          <line x1="25" y1="33" x2="25" y2="65" />
          <line x1="75" y1="33" x2="75" y2="65" />
          <line x1="10" y1="65" x2="90" y2="65" />
        </svg>
      </div>
      <div class="absolute bottom-[20%] right-[8%] h-[220px] w-[220px] rotate-[-8deg] text-[#1c1917]">
        <!-- Second sofa (different angle) -->
        <svg viewBox="0 0 120 60" fill="none" stroke="currentColor" stroke-width="0.7" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <path d="M15 32 L15 48 Q15 54 22 54 L98 54 Q105 54 105 48 L105 32 Q105 26 96 24 L82 22 L70 20 L50 20 L38 22 L24 24 Q15 26 15 32Z" />
          <path d="M22 24 L32 18 L50 15 L70 18 L98 24" />
        </svg>
      </div>
      <div class="absolute left-[25%] top-[45%] h-[100px] w-[100px] rotate-6 opacity-80 text-[#1c1917]">
        <!-- Small accent: cushion/ottoman -->
        <svg viewBox="0 0 50 30" fill="none" stroke="currentColor" stroke-width="0.6" class="h-full w-full" preserveAspectRatio="xMidYMid meet">
          <ellipse cx="25" cy="15" rx="22" ry="12" />
        </svg>
      </div>
    </div>

    <!-- Subtle top border accent -->
    <div class="absolute left-0 right-0 top-0 h-px bg-gradient-to-r from-transparent via-[#1c1917]/15 to-transparent" aria-hidden="true" />

    <div class="relative mx-auto max-w-7xl">
      <div class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#c2410c]">
          Why choose us
        </p>
        <h2 class="font-editorial mt-3 text-3xl font-semibold tracking-tight text-[#1c1917] sm:text-4xl lg:text-[2.5rem]">
          {{ heading }}
        </h2>
        <div class="mx-auto mt-5 h-px w-24 bg-gradient-to-r from-transparent via-[#c2410c]/50 to-transparent" aria-hidden="true" />
      </div>

      <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:mt-20 lg:grid-cols-3 lg:gap-10">
        <div
          v-for="(p, i) in pillars"
          :key="i"
          class="pillar group relative flex flex-col overflow-hidden border border-[#1c1917]/10 bg-white/90 p-8 shadow-[0_1px_3px_0_rgba(28,25,23,0.06)] backdrop-blur-sm transition-all duration-300 hover:border-[#c2410c]/25 hover:shadow-[0_8px_30px_-8px_rgba(28,25,23,0.12)]"
        >
          <span class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-[#c2410c]/5 transition-transform duration-300 group-hover:scale-150" aria-hidden="true" />
          <div class="relative flex h-14 w-14 items-center justify-center border border-[#1c1917]/10 bg-[#faf8f5] text-[#1c1917] transition-colors duration-300 group-hover:border-[#c2410c]/20 group-hover:bg-[#fff8f5]">
            <component :is="p.icon" class="h-7 w-7" stroke-width="1.5" />
          </div>
          <h3 class="font-editorial relative mt-6 text-xl font-semibold text-[#1c1917]">
            {{ p.title }}
          </h3>
          <p class="relative mt-3 flex-1 text-base leading-relaxed text-[#44403c]">
            {{ p.text }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.why-pillars {
  isolation: isolate;
}
</style>
