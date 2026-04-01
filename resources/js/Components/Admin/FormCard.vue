<script setup>
defineProps({
  title: { type: String, default: '' },
  description: { type: String, default: '' },
});
</script>

<template>
  <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50 lg:p-8">
    <div v-if="$slots.vector || title || description" class="mb-6 flex flex-col gap-6 sm:flex-row sm:items-start">
      <div v-if="$slots.vector" class="flex shrink-0 justify-center sm:justify-start">
        <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400" aria-hidden="true">
          <slot name="vector" />
        </div>
      </div>
      <div class="min-w-0 flex-1">
        <h2 v-if="title" class="text-lg font-semibold text-zinc-900 dark:text-white">{{ title }}</h2>
        <p v-if="description" class="mt-1 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ description }}</p>
      </div>
    </div>
    <div class="space-y-5">
      <slot />
    </div>
  </div>
</template>
