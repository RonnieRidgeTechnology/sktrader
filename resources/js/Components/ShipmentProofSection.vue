<script setup>
import { computed } from 'vue';
import { Package, MapPin, Shield, Truck } from 'lucide-vue-next';

const props = defineProps({
  theme: { type: String, default: '' },
  title: { type: String, default: 'Delivery Across Zambia' },
  subtitle: {
    type: String,
    default: 'We deliver sofas and furniture to Lusaka, Copperbelt, Southern, Central, Northern and nationwide. Visit our showroom or order from home.',
  },
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
});

const highlights = [
  { icon: Truck, label: 'Safe delivery', desc: 'We deliver your furniture with care so it arrives in great condition.' },
  { icon: MapPin, label: 'Nationwide', desc: 'From our showroom in Lusaka to your home across Zambia.' },
  { icon: Shield, label: 'Reliable service', desc: 'We work with you to arrange a delivery time that suits you.' },
  { icon: Package, label: 'Quality packaging', desc: 'Furniture is protected for transport so you receive it in perfect condition.' },
];
</script>

<template>
  <section
    class="relative min-w-0 overflow-hidden border-t px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-28"
    :class="isEditorial ? 'border-editorial-ink/10 bg-editorial-cream' : 'border-zinc-200/80 bg-white dark:border-zinc-800 dark:bg-zinc-900'"
    aria-labelledby="worldwide-shipping-heading"
  >
    <div v-if="!isEditorial" class="pointer-events-none absolute -left-40 -top-40 h-80 w-80 rounded-full bg-amber-100/60 blur-3xl dark:bg-amber-900/20" aria-hidden="true" />
    <div v-if="!isEditorial" class="pointer-events-none absolute -right-40 bottom-0 h-72 w-72 rounded-full bg-zinc-100 blur-3xl dark:bg-zinc-800/80" aria-hidden="true" />
    <div v-if="isEditorial" class="pointer-events-none absolute -left-40 -top-40 h-80 w-80 rounded-full bg-editorial-coral/10 blur-3xl" aria-hidden="true" />

    <div class="relative mx-auto min-w-0 max-w-6xl">
      <div class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em]" :class="isEditorial ? 'text-editorial-coral' : 'text-amber-700 dark:text-amber-300'">
          Lusaka & nationwide
        </p>
        <h2
          id="worldwide-shipping-heading"
          class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl lg:text-4xl xl:text-5xl"
          :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'"
        >
          {{ title }}
        </h2>
        <p class="mx-auto mt-4 max-w-2xl text-lg" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">
          {{ subtitle }}
        </p>
      </div>

      <!-- Hub visual: Lusaka at center, regions we deliver to -->
      <div class="mt-16 flex flex-col items-center lg:mt-20">
        <div class="shipping-hub relative flex max-w-2xl flex-wrap items-center justify-center gap-6 sm:gap-8">
          <!-- Center: Lusaka showroom -->
          <div
            class="origin-badge relative z-10 flex flex-col items-center justify-center border-2 px-8 py-6 shadow-lg"
            :class="isEditorial ? 'rounded-sm border-editorial-coral bg-editorial-paper' : 'rounded-none border-2 border-amber-400/60 bg-gradient-to-br from-amber-50 to-amber-100/80 dark:border-amber-500/50 dark:from-amber-950/60 dark:to-amber-900/30'"
          >
            <span class="text-sm font-bold uppercase tracking-wider" :class="isEditorial ? 'text-editorial-ink' : 'text-amber-800 dark:text-amber-200'">Lusaka</span>
            <span class="mt-0.5 text-xs font-medium" :class="isEditorial ? 'text-editorial-slate' : 'text-amber-700/80 dark:text-amber-300/80'">Showroom</span>
          </div>

          <div class="absolute left-1/2 top-1/2 hidden h-px w-full max-w-[280px] -translate-x-1/2 -translate-y-1/2 sm:block" aria-hidden="true">
            <div class="h-full w-full" :class="isEditorial ? 'bg-gradient-to-r from-transparent via-editorial-ink/20 to-transparent' : 'bg-gradient-to-r from-transparent via-amber-300/40 to-transparent dark:via-amber-500/30'" />
          </div>

          <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
            <div
              v-for="r in regions"
              :key="r.name"
              class="region-chip flex items-center border-2 px-4 py-3 transition-all duration-300"
              :class="isEditorial ? 'rounded-sm border-editorial-ink/20 bg-white hover:-translate-y-1 hover:border-editorial-coral' : 'rounded-none border-2 border-zinc-200/80 bg-white shadow-sm hover:-translate-y-1 hover:border-amber-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-amber-500/50'"
            >
              <span class="font-semibold" :class="isEditorial ? 'text-editorial-ink' : 'text-zinc-900 dark:text-white'">{{ r.name }}</span>
            </div>
          </div>
        </div>

        <p class="mt-6 flex items-center justify-center gap-2 text-sm" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-500 dark:text-zinc-400'">
          <MapPin class="h-4 w-4" :class="isEditorial ? 'text-editorial-coral' : 'text-amber-500 dark:text-amber-400'" stroke-width="2" aria-hidden="true" />
          Delivery across Zambia
        </p>
      </div>

      <div class="mt-20 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="(item, i) in highlights"
          :key="item.label"
          class="flex flex-col border p-6 transition-all duration-300"
          :class="isEditorial ? 'rounded-sm border-editorial-ink/20 bg-white hover:border-editorial-coral hover:shadow-md' : 'rounded-none border-2 border-zinc-200/80 bg-zinc-50/80 hover:border-amber-200 hover:bg-amber-50/50 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-amber-500/30 dark:hover:bg-amber-950/20'"
        >
          <div
            class="flex h-12 w-12 items-center justify-center"
            :class="isEditorial ? 'rounded-sm bg-editorial-coral/20 text-editorial-coral' : 'rounded-none bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400'"
            aria-hidden="true"
          >
            <component :is="item.icon" class="h-6 w-6" stroke-width="2" />
          </div>
          <h3 class="mt-4 font-semibold" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">{{ item.label }}</h3>
          <p class="mt-2 flex-1 text-sm leading-relaxed" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">{{ item.desc }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.region-chip:focus {
  @apply outline-none ring-2 ring-amber-400 ring-offset-2 dark:ring-offset-zinc-900;
}
</style>
