<script setup>
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Home, ArrowLeft } from 'lucide-vue-next';
</script>

<template>
  <AppLayout>
    <SeoHead title="Page Not Found (404) | SK Traders" description="The page you are looking for could not be found." :noindex="true" />

    <section class="min-h-[70vh] flex flex-col items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <p class="text-8xl font-bold tracking-tight text-amber-500 dark:text-amber-400">404</p>
        <h1 class="mt-4 text-2xl font-semibold text-zinc-900 dark:text-white sm:text-3xl">
          Page not found
        </h1>
        <p class="mt-3 text-base text-zinc-600 dark:text-zinc-400">
          The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
          <Link
            :href="route('home')"
            class="inline-flex items-center gap-2 rounded-2xl bg-amber-500 px-6 py-3.5 text-base font-semibold text-zinc-900 shadow-lg transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
          >
            <Home class="h-5 w-5" stroke-width="2" />
            Back to home
          </Link>
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-2xl border-2 border-zinc-300 bg-transparent px-6 py-3.5 text-base font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800"
            @click="() => window.history.back()"
          >
            <ArrowLeft class="h-5 w-5" stroke-width="2" />
            Go back
          </button>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
