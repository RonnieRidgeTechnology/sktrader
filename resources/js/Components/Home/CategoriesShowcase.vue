<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Sparkles, ArrowUpRight } from 'lucide-vue-next';

const props = defineProps({
  categories: { type: Array, default: () => [] }, // [{ label, slug }]
  title: { type: String, default: 'Shop by category' },
  subtitle: { type: String, default: 'Explore watches, perfumes, and serums — curated picks with premium presentation.' },
});

const list = computed(() => (Array.isArray(props.categories) ? props.categories : []).filter((c) => c?.slug && c?.label).slice(0, 8));
</script>

<template>
  <section class="border-y border-white/10 bg-luxe-obsidian">
    <div class="luxe-container py-14 sm:py-18 lg:py-20">
      <div class="flex flex-col items-center justify-between gap-6 sm:flex-row sm:items-end">
        <div class="min-w-0">
          <p class="luxe-kicker">Categories</p>
          <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">{{ title }}</h2>
          <p class="mt-3 max-w-2xl text-sm text-luxe-pearl/75 sm:text-base">{{ subtitle }}</p>
        </div>
        <Link :href="route('products')" class="luxe-btn luxe-btn-ghost">
          View all
          <ArrowUpRight class="h-4 w-4" stroke-width="2" />
        </Link>
      </div>

      <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <Link
          v-for="c in list"
          :key="c.slug"
          :href="route('products', { category: c.slug })"
          class="group luxe-surface relative overflow-hidden rounded-4xl p-6 transition hover:bg-white/10"
        >
          <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-luxe-gold/10 blur-2xl" aria-hidden="true" />
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <p class="luxe-kicker">Category</p>
              <p class="mt-3 truncate font-display text-xl font-semibold tracking-tight text-luxe-pearl">
                {{ c.label }}
              </p>
              <p class="mt-2 text-sm text-luxe-mist/85">Explore →</p>
            </div>
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold transition group-hover:border-luxe-gold/30">
              <Sparkles class="h-5 w-5" stroke-width="2" />
            </span>
          </div>
        </Link>
      </div>
    </div>
  </section>
</template>

