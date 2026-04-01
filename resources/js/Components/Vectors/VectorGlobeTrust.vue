<script setup>
/** Vector: global reach / trust – globe with pins and connection lines */
</script>

<template>
  <svg viewBox="0 0 280 280" class="w-full h-full animate-float-slow" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="140" cy="140" r="100" class="stroke-zinc-300 dark:stroke-zinc-600" stroke-width="2" stroke-dasharray="8 6" />
    <circle cx="140" cy="140" r="70" class="fill-amber-100/80 dark:fill-amber-900/20 stroke-amber-400/60 dark:stroke-amber-500/40" stroke-width="1.5" />
    <circle cx="140" cy="140" r="40" class="fill-amber-200/60 dark:fill-amber-800/30 stroke-amber-500 dark:stroke-amber-400" stroke-width="2" />
    <!-- Pins -->
    <g class="fill-amber-500 dark:fill-amber-400">
      <circle cx="140" cy="55" r="8" />
      <circle cx="200" cy="100" r="6" />
      <circle cx="80" cy="100" r="6" />
      <circle cx="180" cy="180" r="6" />
      <circle cx="100" cy="180" r="6" />
    </g>
    <line x1="140" y1="140" x2="140" y2="55" class="stroke-amber-400/50 dark:stroke-amber-500/50" stroke-width="1" />
    <line x1="140" y1="140" x2="200" y2="100" class="stroke-amber-400/50 dark:stroke-amber-500/50" stroke-width="1" />
    <line x1="140" y1="140" x2="80" y2="100" class="stroke-amber-400/50 dark:stroke-amber-500/50" stroke-width="1" />
    <line x1="140" y1="140" x2="180" y2="180" class="stroke-amber-400/50 dark:stroke-amber-500/50" stroke-width="1" />
    <line x1="140" y1="140" x2="100" y2="180" class="stroke-amber-400/50 dark:stroke-amber-500/50" stroke-width="1" />
  </svg>
</template>
