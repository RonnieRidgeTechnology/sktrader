<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const logoUrl = computed(() => usePage().props.zuaaz?.header_logo_url || '/images/logo.png');
</script>

<template>
  <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-editorial-ink px-4 py-12 sm:px-6 lg:px-8">
    <!-- Theme accents -->
    <div
      class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(194,65,12,0.15),transparent)]"
      aria-hidden="true"
    />
    <div
      class="pointer-events-none absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-editorial-coral/5 to-transparent"
      aria-hidden="true"
    />

    <header class="absolute left-0 right-0 top-0 z-10 flex justify-center pt-6 sm:pt-8">
      <Link
        href="/"
        class="flex items-center transition opacity-90 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-offset-2 focus:ring-offset-editorial-ink rounded-lg"
      >
        <img
          :src="logoUrl"
          alt="SK Traders"
          class="h-10 w-auto sm:h-12 brightness-0 invert"
        />
      </Link>
    </header>

    <div class="relative w-full max-w-md">
      <div
        class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/95 px-8 py-10 shadow-2xl backdrop-blur-sm dark:border-zinc-600 dark:bg-zinc-800/95 sm:px-10"
      >
        <div
          class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-editorial-coral/40 to-transparent"
          aria-hidden="true"
        />
        <slot />
      </div>

      <p class="mt-8 text-center text-sm text-white/60">
        Secure access to your account
      </p>
    </div>
  </div>
</template>
