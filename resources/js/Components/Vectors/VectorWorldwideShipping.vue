<script setup>
/** Vector: Nationwide delivery across Zambia – delivery truck, Zambia map outline, routes. */
</script>

<template>
  <svg
    viewBox="0 0 320 220"
    class="w-full h-full"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
  >
    <!-- Soft background -->
    <rect width="320" height="220" rx="12" class="fill-editorial-paper/40 dark:fill-zinc-800/60 stroke-editorial-ink/10 dark:stroke-zinc-600/50" stroke-width="1" />

    <!-- Stylized Zambia outline (simplified shape: north to south, eastern bulge) -->
    <g class="fill-editorial-cream dark:fill-zinc-700/40 stroke-editorial-ink/20 dark:stroke-zinc-500/60" stroke-width="2">
      <path
        d="M 165 42 L 200 48 L 235 65 L 250 95 L 245 135 L 230 175 L 195 200 L 155 205 L 120 195 L 95 165 L 82 125 L 78 85 L 88 55 L 120 42 L 165 42 Z"
      />
    </g>

    <!-- Delivery routes (dashed, from Lusaka to regions) -->
    <g class="route-lines">
      <line x1="155" y1="115" x2="195" y2="55" class="route-line stroke-editorial-coral" stroke-width="2" stroke-dasharray="6 5" stroke-linecap="round" style="animation-delay: 0s" />
      <line x1="155" y1="115" x2="230" y2="100" class="route-line stroke-editorial-coral" stroke-width="2" stroke-dasharray="6 5" stroke-linecap="round" style="animation-delay: 0.15s" />
      <line x1="155" y1="115" x2="155" y2="185" class="route-line stroke-editorial-coral" stroke-width="2" stroke-dasharray="6 5" stroke-linecap="round" style="animation-delay: 0.3s" />
      <line x1="155" y1="115" x2="95" y2="165" class="route-line stroke-editorial-coral" stroke-width="2" stroke-dasharray="6 5" stroke-linecap="round" style="animation-delay: 0.45s" />
    </g>

    <!-- Hub (Lusaka) – origin point -->
    <g class="animate-pulse-soft">
      <circle cx="155" cy="115" r="12" class="fill-editorial-coral/90 stroke-editorial-coral" stroke-width="2.5" />
      <circle cx="155" cy="115" r="5" class="fill-white dark:fill-editorial-ink" />
    </g>

    <!-- Destination dots on map -->
    <circle cx="195" cy="55" r="5" class="fill-editorial-coral/70 dark:fill-editorial-coral/80" />
    <circle cx="230" cy="100" r="5" class="fill-editorial-coral/70 dark:fill-editorial-coral/80" />
    <circle cx="155" cy="185" r="5" class="fill-editorial-coral/70 dark:fill-editorial-coral/80" />
    <circle cx="95" cy="165" r="5" class="fill-editorial-coral/70 dark:fill-editorial-coral/80" />

    <!-- Delivery truck (side view) – main focal -->
    <g class="animate-float" style="animation-duration: 3.5s;">
      <!-- Cargo / container -->
      <rect x="42" y="128" width="72" height="42" rx="6" class="fill-white dark:fill-zinc-600 stroke-editorial-ink/25 dark:stroke-zinc-500" stroke-width="2" />
      <rect x="48" y="134" width="28" height="18" rx="2" class="fill-editorial-paper dark:fill-zinc-500/50 stroke-editorial-ink/15" stroke-width="1" />
      <!-- Cab -->
      <path d="M 114 128 L 114 155 L 148 170 L 148 128 Z" class="fill-editorial-slate/90 dark:fill-zinc-600 stroke-editorial-ink/25 dark:stroke-zinc-500" stroke-width="2" />
      <rect x="118" y="135" width="22" height="18" rx="2" class="fill-sky-200/80 dark:fill-sky-900/50 stroke-editorial-ink/20" stroke-width="1" />
      <!-- Wheels -->
      <circle cx="62" cy="172" r="10" class="fill-editorial-ink/80 dark:fill-zinc-600 stroke-editorial-ink/40" stroke-width="1.5" />
      <circle cx="62" cy="172" r="4" class="fill-editorial-ink/30 dark:fill-zinc-400" />
      <circle cx="102" cy="172" r="10" class="fill-editorial-ink/80 dark:fill-zinc-600 stroke-editorial-ink/40" stroke-width="1.5" />
      <circle cx="102" cy="172" r="4" class="fill-editorial-ink/30 dark:fill-zinc-400" />
      <circle cx="132" cy="172" r="10" class="fill-editorial-coral/90 dark:fill-editorial-coral stroke-editorial-coral" stroke-width="1.5" />
      <circle cx="132" cy="172" r="4" class="fill-white/90 dark:fill-editorial-ink/30" />
    </g>

    <!-- Parcel / box icon (delivered) -->
    <g class="animate-float" style="animation-delay: 0.8s; animation-duration: 3.5s;">
      <rect x="255" y="155" width="36" height="28" rx="4" class="fill-editorial-coral/20 dark:fill-editorial-coral/30 stroke-editorial-coral" stroke-width="2" />
      <path d="M 273 155 L 273 140 L 285 133 L 297 140 L 297 155" class="stroke-editorial-coral" stroke-width="2" fill="none" />
      <path d="M 267 165 L 279 165" class="stroke-editorial-coral/70" stroke-width="1.5" stroke-linecap="round" />
    </g>
  </svg>
</template>

<style scoped>
.route-line {
  animation: route-dash 1.4s linear infinite;
}
@keyframes route-dash {
  0% { stroke-dashoffset: 0; }
  100% { stroke-dashoffset: 22; }
}
</style>
