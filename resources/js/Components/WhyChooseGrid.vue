<script setup>
import { computed } from 'vue';
import { Factory, Package, Tag, Palette, DollarSign, Globe, ShieldCheck, Handshake } from 'lucide-vue-next';

const icons = { Factory, Package, Tag, Palette, DollarSign, Globe, ShieldCheck, Handshake };

const props = defineProps({
  theme: { type: String, default: '' },
  points: {
    type: Array,
    default: () => [
      { icon: 'Package', title: 'Quality sofas', description: 'We stock a wide range of sofas and living room furniture built to last.' },
      { icon: 'Factory', title: 'Showroom in Lusaka', description: 'Visit us to try sofas and see pieces in person before you buy.' },
      { icon: 'Globe', title: 'Nationwide delivery', description: 'We deliver across Zambia so you can shop from anywhere.' },
      { icon: 'Tag', title: 'Fair pricing', description: 'Transparent prices with no hidden costs. Value for money.' },
      { icon: 'Palette', title: 'Style for every home', description: 'From classic to modern — find the look that suits your space.' },
      { icon: 'DollarSign', title: 'Flexible options', description: 'Ready-made pieces or custom orders. We work with your needs.' },
      { icon: 'ShieldCheck', title: 'Warranty & support', description: 'We stand by our quality with warranty and after-sales support.' },
      { icon: 'Handshake', title: 'Friendly service', description: 'A team that cares about helping you find the right furniture.' },
    ],
  },
});

const isEditorial = computed(() => props.theme === 'editorial');
function iconFor(iconName) {
  return icons[iconName] || icons.Factory;
}
</script>

<template>
  <div class="why-choose-infographic">
    <!-- Visual connector line (desktop) -->
    <div class="pointer-events-none absolute left-0 right-0 top-1/2 hidden h-0.5 -translate-y-1/2 md:block" aria-hidden="true">
      <div
        class="mx-auto h-full max-w-5xl"
        :class="isEditorial ? 'rounded-sm bg-editorial-ink/20' : 'rounded-full bg-gradient-to-r from-transparent via-amber-200/60 to-transparent dark:via-amber-800/40'"
      />
    </div>

    <ul class="relative grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <li
        v-for="(point, index) in points"
        :key="index"
        class="group relative flex flex-col border-2 p-6 text-center transition-all duration-300"
        :class="isEditorial
          ? 'rounded-sm border-editorial-ink/20 bg-white hover:-translate-y-1 hover:border-editorial-coral hover:shadow-lg'
          : 'rounded-3xl border-zinc-200/80 bg-white shadow-premium hover:-translate-y-2 hover:border-amber-300 hover:shadow-premium-lg dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-amber-700/50'"
      >
        <span
          class="absolute -right-2 -top-2 flex h-8 w-8 items-center justify-center text-sm font-bold text-white shadow-lg ring-2 ring-white dark:ring-zinc-900"
          :class="isEditorial ? 'rounded-sm bg-editorial-ink' : 'rounded-full bg-black'"
          aria-hidden="true"
        >
          {{ index + 1 }}
        </span>
        <div
          class="mx-auto mb-4 flex h-16 w-16 items-center justify-center text-white transition group-hover:scale-105"
          :class="isEditorial ? 'rounded-sm bg-editorial-coral' : 'rounded-2xl bg-black'"
        >
          <component :is="iconFor(point.icon)" class="h-8 w-8" stroke-width="2" />
        </div>
        <h3 class="text-base font-bold" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">
          {{ point.title }}
        </h3>
        <p class="mt-1.5 text-sm leading-snug" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">
          {{ point.description }}
        </p>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.why-choose-infographic {
  position: relative;
  padding: 0.5rem 0;
}
</style>
