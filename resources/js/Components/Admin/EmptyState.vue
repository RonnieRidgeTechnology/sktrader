<script setup>
import { Inbox } from 'lucide-vue-next';

defineProps({
  icon: { type: Object, default: () => Inbox },
  title: { type: String, default: 'No data yet' },
  description: { type: String, default: '' },
});
</script>

<template>
  <div class="flex flex-col items-center justify-center px-4 py-12 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
      <component :is="icon" class="h-7 w-7 text-zinc-400 dark:text-zinc-500" stroke-width="2" />
    </div>
    <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ title }}</h3>
    <p v-if="description" class="mt-1 max-w-sm text-sm text-zinc-500 dark:text-zinc-400">{{ description }}</p>
    <div v-if="$slots.actions" class="mt-4">
      <slot name="actions" />
    </div>
  </div>
</template>
