<script setup>
/** Vector: Showroom in Lusaka – building/storefront with door, furniture hint */
</script>

<template>
  <svg viewBox="0 0 320 240" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <!-- Showroom building (storefront) -->
    <g class="animate-float" style="animation-duration: 4s;">
      <!-- Main building -->
      <path d="M 50 180 L 50 80 L 270 80 L 270 180 Z" class="fill-white dark:fill-zinc-700 stroke-editorial-ink/20 dark:stroke-zinc-500" stroke-width="2" />
      <!-- Roof / awning -->
      <path d="M 40 80 L 160 50 L 280 80 L 160 95 Z" class="fill-editorial-coral/90 dark:fill-editorial-coral/80 stroke-editorial-coral dark:stroke-editorial-coral" stroke-width="2" />
      <!-- Door -->
      <rect x="135" y="120" width="50" height="60" rx="4" class="fill-editorial-slate/90 dark:fill-zinc-600 stroke-editorial-ink/25 dark:stroke-zinc-500" stroke-width="2" />
      <circle cx="178" cy="150" r="3" class="fill-editorial-cream dark:fill-zinc-400" />
      <!-- Windows -->
      <rect x="70" y="100" width="45" height="35" rx="4" class="fill-sky-100/80 dark:fill-sky-900/40 stroke-editorial-ink/15 dark:stroke-zinc-500" stroke-width="1.5" />
      <rect x="205" y="100" width="45" height="35" rx="4" class="fill-sky-100/80 dark:fill-sky-900/40 stroke-editorial-ink/15 dark:stroke-zinc-500" stroke-width="1.5" />
      <!-- Step -->
      <rect x="130" y="178" width="60" height="8" rx="2" class="fill-editorial-ink/15 dark:fill-zinc-600 stroke-editorial-ink/10" stroke-width="1" />
    </g>
    <!-- Small sofa icon (furniture inside) -->
    <g class="animate-float" style="animation-delay: 0.8s; animation-duration: 4s;">
      <rect x="78" y="145" width="28" height="14" rx="3" class="fill-editorial-coral/30 dark:fill-editorial-coral/40 stroke-editorial-coral/60" stroke-width="1.5" />
    </g>
    <!-- "Visit" pointer / sign -->
    <g class="animate-float opacity-90" style="animation-delay: 1.2s; animation-duration: 4s;">
      <path d="M 160 45 L 165 58 L 178 58 L 168 67 L 171 80 L 160 72 L 149 80 L 152 67 L 142 58 L 155 58 Z" class="fill-amber-400 dark:fill-amber-500" />
    </g>
  </svg>
</template>
