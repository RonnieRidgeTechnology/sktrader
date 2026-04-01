<script setup>
import { computed } from 'vue';
import { Lightbulb, Palette, FileCheck, Factory, Ship } from 'lucide-vue-next';

const props = defineProps({
  theme: { type: String, default: '' },
  scatteredImages: { type: Array, default: () => [] },
});
const isEditorial = computed(() => props.theme === 'editorial');

const icons = { Lightbulb, Palette, FileCheck, Factory, Ship };

const scatteredSlots = computed(() => {
  const positions = [
    { top: '8%', left: '0', right: 'auto' },
    { top: '22%', left: 'auto', right: '0' },
    { top: '38%', left: '0', right: 'auto' },
    { top: '52%', left: 'auto', right: '0' },
    { top: '68%', left: '0', right: 'auto' },
    { top: '82%', left: 'auto', right: '0' },
  ];
  const images = (props.scatteredImages || []).filter(Boolean);
  return positions.map((pos, i) => ({ ...pos, src: images[i % images.length] })).filter(s => s.src);
});

const steps = [
  { icon: 'Lightbulb', title: 'Discover', text: 'Browse watches, perfumes, and serums — curated picks with clear details.' },
  { icon: 'Palette', title: 'Choose', text: 'Pick your style: sizes, finishes, scent profiles, or routine-focused serums.' },
  { icon: 'FileCheck', title: 'Checkout', text: 'Confirm your order. We’ll follow up if we need any detail to dispatch.' },
  { icon: 'Factory', title: 'Pack with care', text: 'We prepare and pack your order carefully for a premium unboxing.' },
  { icon: 'Ship', title: 'Delivery', text: 'Delivery to your address. Contact us any time for order updates.' },
];

function iconFor(iconName) {
  return icons[iconName] || icons.Lightbulb;
}
</script>

<template>
  <div class="relative mx-auto max-w-4xl">
    <!-- Scattered photos (hero / gallery / section images), 130×85 -->
    <template v-if="scatteredSlots.length">
      <div
        v-for="(slot, idx) in scatteredSlots"
        :key="'img-' + idx"
        class="scattered-float pointer-events-none absolute z-0 hidden overflow-hidden rounded-lg shadow-md ring-1 ring-zinc-200/80 dark:ring-zinc-700/80 md:block"
        :style="{
          top: slot.top,
          left: slot.left,
          right: slot.right,
          width: '130px',
          height: '85px',
          animationDelay: (idx * 0.4) + 's',
        }"
        aria-hidden="true"
      >
        <img
          v-if="slot.src"
          :src="slot.src"
          :alt="''"
          class="h-full w-full object-cover"
          loading="lazy"
        />
      </div>
    </template>

    <!-- Wavy line (desktop) -->
    <div class="absolute left-1/2 top-0 z-10 hidden h-full w-20 -translate-x-1/2 md:block" aria-hidden="true">
      <svg viewBox="0 0 200 800" class="h-full w-full" :class="isEditorial ? 'text-editorial-ink/30' : 'text-zinc-300 dark:text-zinc-600'" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M 100 30 C 170 90, 170 150, 100 190 C 30 230, 30 290, 100 330 C 170 370, 170 430, 100 470 C 30 510, 30 570, 100 610 C 170 650, 170 710, 100 770"
          stroke="currentColor"
          stroke-width="2.5"
          stroke-linecap="round"
          stroke-linejoin="round"
          style="stroke-dasharray: 1400; stroke-dashoffset: 1400; animation: draw-line 1.8s ease-out 0.2s forwards;"
        />
      </svg>
    </div>

    <!-- Vertical line (mobile) -->
    <div class="absolute left-5 top-0 z-10 h-full w-1 md:hidden" :class="isEditorial ? 'rounded-sm bg-editorial-coral/60' : 'rounded-full bg-gradient-to-b from-amber-400 via-amber-300 to-orange-400 dark:from-amber-500 dark:to-amber-600'" aria-hidden="true" />

    <ul class="relative z-10">
      <li
        v-for="(step, index) in steps"
        :key="index"
        class="relative grid min-h-[100px] grid-cols-1 items-center py-4 md:grid-cols-2 md:py-3"
      >
        <!-- Center node (desktop) -->
        <div
          class="absolute left-1/2 top-1/2 hidden h-4 w-4 -translate-x-1/2 -translate-y-1/2 shadow-lg ring-4 ring-white dark:ring-zinc-900 md:block how-step-dot"
          :class="isEditorial ? 'rounded-sm bg-editorial-coral ring-2 ring-white' : 'rounded-full bg-amber-500 shadow-lg ring-4 ring-white dark:ring-zinc-900'"
          :style="{ top: `${(index + 0.5) * (100 / steps.length)}%`, animationDelay: (0.5 + index * 0.12) + 's' }"
        />

        <!-- Single card: on mobile full width with left padding; on desktop in left or right column -->
        <div
          class="how-step-card col-start-1 w-full border-2 px-4 py-4 backdrop-blur pl-14 md:max-w-xs md:pl-4"
          :class="[
            index % 2 === 0 ? 'md:col-start-2 md:justify-self-start md:ml-4' : 'md:justify-self-end md:mr-4',
            isEditorial ? 'rounded-sm border-editorial-ink/25 bg-editorial-cream/95 shadow-md' : 'rounded-2xl border-zinc-200/90 bg-white/95 shadow-soft',
          ]"
          :style="{ animationDelay: (0.35 + index * 0.12) + 's' }"
        >
          <div class="flex items-center gap-4">
            <div
              class="flex h-11 w-11 shrink-0 items-center justify-center text-sm font-bold text-white shadow-md"
              :class="isEditorial ? 'rounded-sm bg-editorial-ink ring-2 ring-editorial-ink/20' : 'rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 ring-2 ring-amber-100 dark:ring-amber-900/40'"
            >
              {{ index + 1 }}
            </div>
            <div
              class="flex h-10 w-10 shrink-0 items-center justify-center"
              :class="isEditorial ? 'rounded-sm bg-editorial-paper' : 'rounded-xl bg-zinc-100 dark:bg-zinc-700'"
            >
              <component :is="iconFor(step.icon)" class="h-5 w-5" :class="isEditorial ? 'text-editorial-ink' : 'text-zinc-700 dark:text-zinc-200'" />
            </div>
            <div class="min-w-0 flex-1">
              <h3 class="font-bold" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">{{ step.title }}</h3>
              <p class="mt-0.5 text-sm leading-relaxed" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">{{ step.text }}</p>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.scattered-float {
  animation: scattered-float 4s ease-in-out infinite;
}
@keyframes scattered-float {
  0%, 100% {
    transform: translateY(0) translateX(0) rotate(0deg);
  }
  25% {
    transform: translateY(-6px) translateX(3px) rotate(0.5deg);
  }
  50% {
    transform: translateY(0) translateX(-2px) rotate(-0.3deg);
  }
  75% {
    transform: translateY(5px) translateX(2px) rotate(0.4deg);
  }
}
.how-step-card {
  animation: how-step-in 0.5s ease-out forwards;
  opacity: 1;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.how-step-card:hover {
  transform: scale(1.02);
  box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
}
.how-step-card:not(.border-editorial-ink\/25):hover {
  border-color: rgb(251 191 36 / 0.5);
}
:global(.dark) .how-step-card:not(.border-editorial-ink\/25) {
  border-color: rgb(63 63 70 / 0.9);
  background: rgb(39 39 42 / 0.95);
}
:global(.dark) .how-step-card:not(.border-editorial-ink\/25):hover {
  border-color: rgb(251 191 36 / 0.4);
}

.how-step-dot {
  animation: how-step-in 0.4s ease-out forwards;
  opacity: 1;
}
</style>
<style>
@keyframes how-step-in {
  from {
    opacity: 0.7;
    transform: scale(0.96) translateY(8px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}
</style>
