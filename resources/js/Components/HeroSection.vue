<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  theme: { type: String, default: '' }, // 'editorial' = cream/ink/coral, serif, sharp
  whatsappLink: { type: String, required: true },
  headline: { type: String, default: 'Luxury essentials, curated.' },
  subheading: { type: String, default: 'Watches, perfumes, and skin care serums — premium picks, delivered with care.' },
  sliderImages: { type: Array, default: () => [] },
});
const isEditorial = computed(() => props.theme === 'editorial');

// Normalize to array of URL strings (relative /media/... or full URLs)
const slides = computed(() => {
  const arr = Array.isArray(props.sliderImages) ? props.sliderImages : [];
  return arr
    .map((u) => (typeof u === 'string' ? u.trim() : null))
    .filter((u) => u && u.length > 0);
});
const hasSlides = computed(() => slides.value.length > 0);

const currentIndex = ref(0);
const INTERVAL_MS = 6000;
let intervalId = null;

function goTo(index) {
  const n = slides.value.length;
  if (n === 0) return;
  currentIndex.value = ((index % n) + n) % n;
}

function next() {
  goTo(currentIndex.value + 1);
}

function prev() {
  goTo(currentIndex.value - 1);
}

function startAutoPlay() {
  if (slides.value.length <= 1) return;
  intervalId = setInterval(next, INTERVAL_MS);
}

function stopAutoPlay() {
  if (intervalId) {
    clearInterval(intervalId);
    intervalId = null;
  }
}

onMounted(() => {
  startAutoPlay();
});
onUnmounted(() => {
  stopAutoPlay();
});
</script>

<template>
  <section
    class="relative min-h-[85vh] overflow-hidden sm:min-h-[90vh] lg:min-h-[95vh]"
    :class="isEditorial ? 'bg-editorial-cream' : 'bg-zinc-950'"
    aria-label="Hero"
    @mouseenter="stopAutoPlay"
    @mouseleave="startAutoPlay"
  >
    <!-- Slider background -->
    <div class="absolute inset-0 z-0">
      <template v-if="hasSlides">
        <div
          v-for="(url, i) in slides"
          :key="`slide-${i}-${url}`"
          class="absolute inset-0 transition-opacity duration-700 ease-out"
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
      <div
        v-else
        class="absolute inset-0"
        :class="isEditorial ? 'bg-editorial-paper' : 'bg-gradient-to-br from-zinc-800 via-zinc-900 to-zinc-950'"
        aria-hidden="true"
      />
      <!-- Overlay: editorial = light cream tint; default = dark -->
      <div
        class="absolute inset-0 z-20"
        :class="isEditorial ? 'bg-editorial-cream/85' : 'bg-zinc-950/70'"
        aria-hidden="true"
      />
    </div>

    <!-- Content -->
    <div
      class="relative z-30 mx-auto flex min-h-[85vh] max-w-7xl flex-col justify-end px-4 pb-16 pt-8 sm:min-h-[90vh] sm:px-6 sm:pb-20 sm:pt-10 lg:min-h-[95vh] lg:px-8 lg:pb-24 lg:pt-12"
      :class="isEditorial ? 'justify-end text-left' : ''"
    >
      <div
        class="max-w-4xl"
        :class="isEditorial ? 'ml-0 font-editorial' : 'mx-auto text-center'"
      >
        <h1
          class="text-3xl font-bold leading-tight tracking-tight sm:text-4xl md:text-5xl lg:text-6xl"
          :class="isEditorial ? 'text-editorial-ink' : 'text-white drop-shadow-lg'"
        >
          {{ headline }}
        </h1>
        <p
          class="mt-4 text-xl font-semibold sm:text-2xl"
          :class="isEditorial ? 'text-editorial-coral' : 'text-amber-400 drop-shadow'"
        >
          {{ subheading }}
        </p>

        <div
          class="mt-10 flex flex-wrap gap-4"
          :class="isEditorial ? '' : 'items-center justify-center'"
        >
          <a
            :href="whatsappLink"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex min-h-[48px] items-center justify-center gap-2 px-6 py-3 text-base font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="isEditorial
              ? 'rounded-sm bg-[#128C7E] text-white hover:bg-[#0d7a6f] focus:ring-[#128C7E] focus:ring-offset-editorial-cream'
              : 'rounded-xl bg-[#128C7E] text-white shadow-lg hover:bg-[#0d7a6f] focus:ring-[#128C7E] focus:ring-offset-zinc-950'"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Chat on WhatsApp
          </a>
          <Link
            :href="route('products')"
            class="inline-flex min-h-[48px] items-center justify-center gap-2 px-6 py-3 text-base font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="isEditorial
              ? 'rounded-sm border-2 border-editorial-ink bg-transparent text-editorial-ink hover:bg-editorial-ink hover:text-editorial-cream focus:ring-editorial-ink focus:ring-offset-editorial-cream'
              : 'rounded-xl bg-amber-500 text-zinc-900 shadow-lg hover:bg-amber-400 focus:ring-amber-400 focus:ring-offset-zinc-950'"
          >
            Buy Now
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>
      </div>
    </div>

    <!-- Slider controls (only when multiple slides) -->
    <template v-if="slides.length > 1">
      <button
        type="button"
        class="absolute left-2 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center backdrop-blur transition focus:outline-none focus:ring-2 sm:left-4"
        :class="isEditorial ? 'rounded-sm bg-editorial-ink/90 text-editorial-cream hover:bg-editorial-ink focus:ring-editorial-coral' : 'rounded-full bg-zinc-900/80 text-white shadow-xl hover:bg-zinc-800 focus:ring-amber-500'"
        aria-label="Previous slide"
        @click="prev"
      >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button
        type="button"
        class="absolute right-2 top-1/2 z-40 flex h-12 w-12 -translate-y-1/2 items-center justify-center backdrop-blur transition focus:outline-none focus:ring-2 sm:right-4"
        :class="isEditorial ? 'rounded-sm bg-editorial-ink/90 text-editorial-cream hover:bg-editorial-ink focus:ring-editorial-coral' : 'rounded-full bg-zinc-900/80 text-white shadow-xl hover:bg-zinc-800 focus:ring-amber-500'"
        aria-label="Next slide"
        @click="next"
      >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div
        class="absolute bottom-6 left-1/2 z-40 flex -translate-x-1/2 gap-2"
        role="tablist"
        aria-label="Slide navigation"
      >
        <button
          v-for="(_, i) in slides"
          :key="i"
          type="button"
          :aria-label="`Go to slide ${i + 1}`"
          :aria-selected="i === currentIndex"
          class="h-2.5 w-2.5 transition-all"
          :class="[
            i === currentIndex ? 'scale-125' : '',
            isEditorial
              ? (i === currentIndex ? 'rounded-full bg-editorial-coral' : 'rounded-full bg-editorial-ink/40 hover:bg-editorial-ink/60')
              : (i === currentIndex ? 'rounded-full bg-amber-500' : 'rounded-full bg-white/50 hover:bg-white/80'),
          ]"
          @click="goTo(i)"
        />
      </div>
    </template>
  </section>
</template>
