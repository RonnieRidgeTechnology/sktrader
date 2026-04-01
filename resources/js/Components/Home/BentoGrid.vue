<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  images: { type: Array, default: () => [] },
  tagline: { type: String, default: 'Lusaka showroom · Nationwide delivery' },
});

const cells = computed(() => {
  const list = (Array.isArray(props.images) ? props.images : []).filter(Boolean).slice(0, 4);
  const layouts = [
    { gridRow: '1 / span 2', gridCol: '1 / span 2', image: list[0] || null, isText: false },
    { gridRow: '1', gridCol: '3', image: list[1] || null, isText: false },
    { gridRow: '1', gridCol: '4', image: list[2] || null, isText: false },
    { gridRow: '2', gridCol: '3', image: list[3] || null, isText: false },
    { gridRow: '2', gridCol: '4', image: null, isText: true },
  ];
  return layouts;
});
</script>

<template>
  <section class="bento-section bg-[#0f0e0d] px-4 py-16 sm:px-6 sm:py-24 md:px-8 lg:px-12" aria-label="Lookbook">
    <div class="mx-auto max-w-7xl">
      <p class="mb-10 text-center text-xs font-medium uppercase tracking-[0.3em] text-white/60">
        The collection
      </p>
      <div
        class="bento-grid grid auto-rows-[minmax(140px,1fr)] grid-cols-4 gap-2 sm:gap-3 md:auto-rows-[minmax(180px,1fr)]"
      >
        <template v-for="(cell, idx) in cells" :key="idx">
          <!-- Text cell -->
          <div
            v-if="cell.isText"
            class="group flex flex-col justify-end border border-white/15 bg-white/5 p-4 backdrop-blur-sm transition hover:bg-white/10 sm:p-6"
            :style="{ gridRow: cell.gridRow, gridColumn: cell.gridCol }"
          >
            <p class="font-editorial text-lg font-semibold text-white sm:text-xl">
              {{ tagline }}
            </p>
            <Link
              :href="route('products')"
              class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white/80 underline-offset-4 transition hover:text-white"
            >
              View all pieces
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </Link>
          </div>
          <!-- Image cell -->
          <Link
            v-else-if="cell.image"
            :href="route('products')"
            class="group relative block overflow-hidden border border-white/10 transition hover:border-white/25"
            :style="{ gridRow: cell.gridRow, gridColumn: cell.gridCol }"
          >
            <img
              :src="cell.image"
              :alt="`Collection ${idx + 1}`"
              class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
            <span class="absolute bottom-3 left-3 text-xs font-medium uppercase tracking-wider text-white/90 opacity-0 transition-opacity group-hover:opacity-100">
              View collection
            </span>
          </Link>
          <!-- Placeholder -->
          <div
            v-else
            class="flex items-center justify-center border border-white/10 bg-white/5"
            :style="{ gridRow: cell.gridRow, gridColumn: cell.gridCol }"
          >
            <span class="text-xs text-white/40">—</span>
          </div>
        </template>
      </div>
    </div>
  </section>
</template>

<style scoped>
.bento-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: repeat(2, minmax(0, 1fr));
}
@media (max-width: 640px) {
  .bento-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: minmax(120px, 1fr);
  }
  .bento-grid > * {
    grid-column: span 1 !important;
    grid-row: auto !important;
  }
}
</style>
