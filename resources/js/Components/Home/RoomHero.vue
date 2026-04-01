<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  whatsappLink: { type: String, required: true },
  headline: { type: String, default: 'Quality Sofas & Furniture for Every Home' },
  subheading: { type: String, default: 'Comfort, Style & Value in Zambia' },
  sliderImages: { type: Array, default: () => [] },
});

const slides = computed(() => {
  const arr = Array.isArray(props.sliderImages) ? props.sliderImages : [];
  return arr.map((u) => (typeof u === 'string' ? u.trim() : null)).filter((u) => u && u.length > 0);
});
const hasSlides = computed(() => slides.value.length > 0);
const currentIndex = ref(0);
const INTERVAL_MS = 2000;
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
</script>

<template>
  <section
    class="room-hero relative flex min-h-[100vh] min-h-[100dvh] flex-col justify-end overflow-hidden bg-[#0f0e0d]"
    aria-label="Hero"
    @mouseenter="stopAutoPlay"
    @mouseleave="startAutoPlay"
  >
    <!-- Background: one image or gradient -->
    <div class="absolute inset-0">
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
      <div v-else class="absolute inset-0 bg-gradient-to-br from-[#1a1918] via-[#151413] to-[#0f0e0d]" />
      <div class="absolute inset-0 z-20 bg-[#0f0e0d]/40" aria-hidden="true" />
    </div>

    <!-- Content: bottom, editorial -->
    <div class="relative z-30 px-4 pb-[clamp(2rem,8vw,4rem)] pt-20 sm:px-6 md:px-8 lg:px-12">
      <div class="mx-auto max-w-7xl">
        <p class="mb-2 text-xs font-medium uppercase tracking-[0.35em] text-white/70">
          Living Room · Lusaka
        </p>
        <h1 class="font-editorial max-w-3xl text-[clamp(2.25rem,5vw,4rem)] font-semibold leading-[1.1] tracking-tight text-white">
          {{ headline }}
        </h1>
        <p class="mt-4 max-w-xl text-lg text-white/85 sm:text-xl">
          {{ subheading }}
        </p>
        <div class="mt-10 flex flex-wrap gap-4">
          <a
            :href="whatsappLink"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex min-h-[52px] items-center justify-center gap-2 border-2 border-white bg-white px-6 py-3.5 text-base font-semibold text-[#0f0e0d] transition hover:bg-white/95 hover:border-white/95"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Chat on WhatsApp
          </a>
          <Link
            :href="route('products')"
            class="inline-flex min-h-[52px] items-center justify-center gap-2 border-2 border-white/80 bg-transparent px-6 py-3.5 text-base font-semibold text-white transition hover:bg-white/10 hover:border-white"
          >
            Explore collection
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
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
          :class="i === currentIndex ? 'bg-white' : 'bg-white/40 hover:bg-white/60'"
          @click="goTo(i)"
        />
      </div>
      <button
        type="button"
        class="absolute left-4 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center border border-white/30 bg-black/20 text-white transition hover:bg-black/40 md:left-8"
        aria-label="Previous"
        @click="prev"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
      </button>
      <button
        type="button"
        class="absolute right-4 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center border border-white/30 bg-black/20 text-white transition hover:bg-black/40 md:right-8"
        aria-label="Next"
        @click="next"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
      </button>
    </template>
  </section>
</template>
