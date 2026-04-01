<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  whatsappLink: { type: String, required: true },
  headline: { type: String, default: 'Luxury essentials, curated.' },
  subheading: { type: String, default: 'Watches, perfumes, and skincare serums — premium picks, delivered with care.' },
  sliderImages: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] }, // [{ label, slug }]
});

const slides = computed(() => {
  const arr = Array.isArray(props.sliderImages) ? props.sliderImages : [];
  return arr.map((u) => (typeof u === 'string' ? u.trim() : null)).filter((u) => u && u.length > 0);
});
const hasSlides = computed(() => slides.value.length > 0);
const currentIndex = ref(0);
const INTERVAL_MS = 4500;
let intervalId = null;

function goTo(i) {
  if (!slides.value.length) return;
  currentIndex.value = ((i % slides.value.length) + slides.value.length) % slides.value.length;
}
function next() { goTo(currentIndex.value + 1); }
function prev() { goTo(currentIndex.value - 1); }
function startAutoPlay() {
  if (slides.value.length <= 1) return;
  intervalId = setInterval(next, INTERVAL_MS);
}
function stopAutoPlay() {
  if (intervalId) clearInterval(intervalId);
  intervalId = null;
}
onMounted(() => startAutoPlay());
onUnmounted(() => stopAutoPlay());

const topCategories = computed(() => {
  const list = Array.isArray(props.categories) ? props.categories : [];
  return list.slice(0, 3);
});
</script>

<template>
  <section
    class="room-hero relative flex min-h-[100vh] min-h-[100dvh] flex-col justify-end overflow-hidden bg-luxe-obsidian"
    aria-label="Hero"
    @mouseenter="stopAutoPlay"
    @mouseleave="startAutoPlay"
  >
    <!-- Background: cinematic (images or gradient) -->
    <div class="absolute inset-0 bg-luxe-radial">
      <template v-if="hasSlides">
        <div
          v-for="(url, i) in slides"
          :key="i"
          class="absolute inset-0 transition-opacity duration-[1200ms] ease-out"
          :class="i === currentIndex ? 'opacity-100 z-10' : 'opacity-0 z-0'"
        >
          <img
            :src="url"
            :alt="`Slide ${i + 1}`"
            class="h-full w-full object-cover"
            loading="eager"
          />
        </div>
      </template>
      <div v-else class="absolute inset-0 bg-gradient-to-br from-luxe-carbon via-luxe-obsidian to-black" />
      <div class="absolute inset-0 z-20 bg-gradient-to-t from-black/75 via-black/35 to-black/25" aria-hidden="true" />
      <div class="absolute inset-0 z-30 bg-[radial-gradient(900px_500px_at_30%_40%,rgba(255,255,255,0.08),transparent_60%)]" aria-hidden="true" />
    </div>

    <!-- Content -->
    <div class="relative z-40 px-4 pb-[clamp(2rem,8vw,5rem)] pt-24 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <p class="luxe-kicker">
          Curated luxury · Limited drops · Secure checkout
        </p>
        <h1 class="luxe-title mt-4 max-w-3xl text-[clamp(2.4rem,5.2vw,4.6rem)] font-semibold leading-[1.02]">
          {{ headline }}
        </h1>
        <p class="mt-5 max-w-xl text-lg text-luxe-pearl/85 sm:text-xl">
          {{ subheading }}
        </p>
        <div class="mt-10 flex flex-wrap gap-3">
          <Link :href="route('products')" class="luxe-btn luxe-btn-primary">
            Explore the collection
            <span class="ml-1">→</span>
          </Link>
          <a :href="whatsappLink" target="_blank" rel="noopener noreferrer" class="luxe-btn luxe-btn-ghost">
            WhatsApp concierge
          </a>
        </div>

        <!-- Category gateways -->
        <div v-if="topCategories.length" class="mt-12 grid gap-3 sm:grid-cols-3">
          <Link
            v-for="c in topCategories"
            :key="c.slug"
            :href="route('products', { category: c.slug })"
            class="luxe-surface rounded-3xl p-5 transition hover:bg-white/10"
          >
            <p class="luxe-kicker">Category</p>
            <p class="mt-2 font-display text-xl font-semibold tracking-tight text-luxe-pearl">{{ c.label }}</p>
            <p class="mt-2 text-sm text-luxe-mist/80">Shop now →</p>
          </Link>
        </div>
      </div>
    </div>

    <!-- Slide nav (only if multiple) -->
    <template v-if="slides.length > 1">
      <div class="absolute bottom-6 left-1/2 z-40 flex -translate-x-1/2 gap-2" role="tablist" aria-label="Slides">
        <button
          v-for="(_, i) in slides"
          :key="i"
          type="button"
          :aria-label="`Slide ${i + 1}`"
          :aria-selected="i === currentIndex"
          class="h-1 w-8 transition-all duration-300"
          :class="i === currentIndex ? 'bg-luxe-gold' : 'bg-white/30 hover:bg-white/45'"
          @click="goTo(i)"
        />
      </div>
      <button
        type="button"
        class="absolute left-4 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-3xl border border-white/15 bg-white/5 text-white backdrop-blur transition hover:bg-white/10 md:left-8"
        aria-label="Previous"
        @click="prev"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
      </button>
      <button
        type="button"
        class="absolute right-4 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-3xl border border-white/15 bg-white/5 text-white backdrop-blur transition hover:bg-white/10 md:right-8"
        aria-label="Next"
        @click="next"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
      </button>
    </template>
  </section>
</template>
