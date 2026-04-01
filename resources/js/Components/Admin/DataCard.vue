<script setup>
defineProps({
  class: { type: String, default: '' },
  padded: { type: Boolean, default: false },
});
</script>

<template>
  <div
    class="overflow-hidden rounded-2xl border border-zinc-200/80 bg-white shadow-[0_1px_3px_rgba(0,0,0,0.04),0_6px_16px_rgba(0,0,0,0.04)] transition-shadow duration-200 hover:shadow-[0_4px_12px_rgba(0,0,0,0.06),0_12px_24px_rgba(0,0,0,0.06)] dark:border-zinc-700/80 dark:bg-zinc-900/80 dark:shadow-none dark:hover:shadow-lg"
    :class="[$props.class, padded ? 'p-6' : '']"
  >
    <slot />
  </div>
</template>
