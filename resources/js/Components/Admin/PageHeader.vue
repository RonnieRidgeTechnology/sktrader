<script setup>
defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
});
</script>

<template>
  <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
        {{ title }}
      </h1>
      <p v-if="description" class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
        {{ description }}
      </p>
    </div>
    <div v-if="$slots.actions" class="mt-4 shrink-0 sm:mt-0">
      <slot name="actions" />
    </div>
  </div>
</template>
