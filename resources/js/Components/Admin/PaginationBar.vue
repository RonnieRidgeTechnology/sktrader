<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  data: { type: Object, default: () => ({}) },
});
const d = props.data ?? {};
const from = d.from ?? 0;
const to = d.to ?? 0;
const total = d.total ?? 0;
const prev = d.prev_page_url;
const next = d.next_page_url;
const hasPages = prev || next;
</script>

<template>
  <div
    v-if="hasPages || total > 0"
    class="flex flex-col items-center justify-between gap-2 border-t border-zinc-200 px-4 py-3 dark:border-zinc-700 sm:flex-row"
  >
    <p class="text-sm text-zinc-500 dark:text-zinc-400">
      Showing <span class="font-medium text-zinc-900 dark:text-white">{{ from }}</span> to <span class="font-medium text-zinc-900 dark:text-white">{{ to }}</span> of <span class="font-medium text-zinc-900 dark:text-white">{{ total }}</span> results
    </p>
    <div class="flex gap-2">
      <Link
        v-if="prev"
        :href="prev"
        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
      >
        Previous
      </Link>
      <Link
        v-if="next"
        :href="next"
        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
      >
        Next
      </Link>
    </div>
  </div>
</template>
