<script setup>
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Home, ArrowLeft } from 'lucide-vue-next';
</script>

<template>
  <AppLayout>
    <SeoHead title="Page Not Found (404) | SK Traders" description="The page you are looking for could not be found." :noindex="true" />

    <section class="min-h-[70vh] flex flex-col items-center justify-center px-4 py-16 sm:px-6 lg:px-8 bg-luxe-obsidian">
      <div class="mx-auto max-w-2xl text-center">
        <p class="text-[clamp(4rem,10vw,7rem)] font-display font-semibold tracking-tight text-luxe-gold">404</p>
        <h1 class="mt-5 text-2xl font-semibold text-luxe-pearl sm:text-3xl">
          Page not found
        </h1>
        <p class="mt-4 text-base text-luxe-pearl/80">
          The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
          <Link
            :href="route('home')"
            class="luxe-btn luxe-btn-primary"
          >
            <Home class="h-5 w-5" stroke-width="2" />
            Back to home
          </Link>
          <button
            type="button"
            class="luxe-btn luxe-btn-ghost"
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
