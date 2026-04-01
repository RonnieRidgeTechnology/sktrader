<script setup>
import { computed } from 'vue';
import { Lightbulb, Palette, FileCheck, Factory, Truck } from 'lucide-vue-next';

const defaultSteps = [
  { icon: Lightbulb, title: 'Discover', line: 'Browse curated watches, perfumes, and serums.' },
  { icon: Palette, title: 'Choose', line: 'Pick your style, scent profile, or routine goal.' },
  { icon: FileCheck, title: 'Checkout', line: 'Confirm order and delivery details.' },
  { icon: Factory, title: 'Pack with care', line: 'Premium handling and careful packing.' },
  { icon: Truck, title: 'Delivery', line: 'Delivered with confidence.' },
];
const icons = [Lightbulb, Palette, FileCheck, Factory, Truck];
const props = defineProps({ section: { type: Object, default: null } });
const eyebrow = computed(() => props.section?.eyebrow || 'From idea to home');
const steps = computed(() => {
  const raw = props.section?.steps;
  if (!Array.isArray(raw) || raw.length < 5) return defaultSteps;
  return raw.slice(0, 5).map((s, i) => ({
    icon: icons[i] || Lightbulb,
    title: s?.title || defaultSteps[i]?.title || '',
    line: s?.line || defaultSteps[i]?.line || '',
  }));
});
</script>

<template>
  <section class="journey-strip overflow-hidden bg-[#1c1917] py-14 sm:py-20" aria-label="How it works">
    <p class="mb-10 text-center text-xs font-medium uppercase tracking-[0.3em] text-white/60">
      {{ eyebrow }}
    </p>
    <div class="journey-scroll flex snap-x snap-mandatory gap-8 overflow-x-auto px-4 pb-4 sm:gap-12 sm:px-6 md:px-8 lg:gap-16 lg:px-12" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
      <div class="mx-auto flex min-w-0 max-w-7xl flex-shrink-0 snap-center gap-8 sm:gap-12 md:flex-row md:gap-16 lg:gap-20">
        <div
          v-for="(step, i) in steps"
          :key="i"
          class="journey-step flex min-w-[200px] max-w-[220px] flex-shrink-0 flex-col items-center text-center sm:min-w-[240px]"
        >
          <span class="font-editorial text-4xl font-semibold text-white/30 sm:text-5xl">{{ String(i + 1).padStart(2, '0') }}</span>
          <div class="mt-4 flex h-14 w-14 items-center justify-center border border-white/20 bg-white/5 text-white/90">
            <component :is="step.icon" class="h-7 w-7" stroke-width="1.5" />
          </div>
          <h3 class="mt-4 font-editorial text-lg font-semibold text-white">
            {{ step.title }}
          </h3>
          <p class="mt-2 text-sm leading-snug text-white/70">
            {{ step.line }}
          </p>
        </div>
      </div>
    </div>
    <p class="mt-8 text-center text-sm text-white/50" style="scrollbar-width: none;">
      Drag to explore
    </p>
  </section>
</template>

<style scoped>
.journey-scroll::-webkit-scrollbar {
  display: none;
}
</style>
