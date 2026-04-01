<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Check, Package, Palette, Truck, Shield, FileCheck, Tag } from 'lucide-vue-next';

const icons = { Check, Package, Palette, Truck, Shield, FileCheck, Tag };

const props = defineProps({
  theme: { type: String, default: '' },
  icon: { type: String, default: 'Check' },
  title: { type: String, required: true },
  description: { type: String, default: '' },
  number: { type: [Number, String], default: null },
});
const isEditorial = computed(() => props.theme === 'editorial');

const iconComponent = computed(() => icons[props.icon] || Check);
const primaryColor = computed(() => usePage().props.zuaaz?.primary_color || '#000000');
</script>

<template>
  <div
    class="group relative border bg-white p-6 transition-all duration-300"
    :class="isEditorial
      ? 'rounded-sm border-editorial-ink/20 hover:-translate-y-1 hover:border-editorial-coral hover:shadow-lg'
      : 'rounded-3xl border-zinc-200/80 shadow-premium hover:-translate-y-2 hover:border-amber-200 hover:shadow-premium-lg dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-amber-900/50'"
  >
    <span
      v-if="number != null"
      class="absolute -right-2 -top-2 flex h-8 w-8 items-center justify-center text-sm font-bold text-white shadow-lg ring-2 ring-white dark:ring-zinc-900"
      :class="isEditorial ? 'rounded-sm bg-editorial-ink' : 'rounded-full bg-black'"
      aria-hidden="true"
    >
      {{ number }}
    </span>
    <div
      class="mb-4 flex h-14 w-14 items-center justify-center text-white shadow-lg transition duration-300 group-hover:scale-110"
      :class="isEditorial ? 'rounded-sm bg-editorial-coral' : 'rounded-2xl'"
      :style="!isEditorial ? { backgroundColor: primaryColor } : undefined"
    >
      <component :is="iconComponent" class="h-7 w-7" />
    </div>
    <h3 class="text-lg font-semibold" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">{{ title }}</h3>
    <p v-if="description" class="mt-2 text-sm leading-relaxed" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">{{ description }}</p>
  </div>
</template>
