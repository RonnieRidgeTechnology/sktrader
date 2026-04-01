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
const currentSlide = computed(() => (slides.value.length ? slides.value[currentIndex.value] : null));
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
    class="room-hero relative flex min-h-[100vh] min-h-[100dvh] flex-col overflow-hidden bg-luxe-obsidian"
    aria-label="Hero"
    @mouseenter="stopAutoPlay"
    @mouseleave="startAutoPlay"
  >
    <!-- Background: cinematic + grain -->
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
      <div class="absolute inset-0 z-20 bg-gradient-to-b from-black/55 via-black/45 to-black/70" aria-hidden="true" />
      <div class="absolute inset-0 z-30 bg-[radial-gradient(900px_500px_at_20%_20%,rgba(199,164,93,0.18),transparent_60%)]" aria-hidden="true" />
      <div class="absolute inset-0 z-30 opacity-[0.08] mix-blend-overlay [background-image:url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2260%22%20height%3D%2260%22%3E%3Cfilter%20id%3D%22n%22%3E%3CfeTurbulence%20type%3D%22fractalNoise%22%20baseFrequency%3D%220.8%22%20numOctaves%3D%222%22%20stitchTiles%3D%22stitch%22/%3E%3C/filter%3E%3Crect%20width%3D%2260%22%20height%3D%2260%22%20filter%3D%22url(%23n)%22%20opacity%3D%221%22/%3E%3C/svg%3E')]" aria-hidden="true" />
    </div>

    <!-- Content: split editorial (very different layout) -->
    <div class="relative z-40 flex flex-1 items-center">
      <div class="luxe-container py-24 sm:py-28">
        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
          <!-- Left: copy in glass panel -->
          <div class="lg:col-span-6">
            <div class="luxe-surface-strong rounded-5xl p-7 sm:p-10">
              <p class="luxe-kicker">Curated luxury · Limited drops · Secure checkout</p>
              <h1 class="luxe-title mt-5 text-[clamp(2.2rem,4.6vw,4rem)] font-semibold leading-[1.03]">
                {{ headline }}
              </h1>
              <p class="mt-5 max-w-xl text-base text-luxe-pearl/80 sm:text-lg">
                {{ subheading }}
              </p>

              <div class="mt-8 flex flex-wrap gap-3">
                <Link :href="route('products')" class="luxe-btn luxe-btn-primary">
                  Shop now
                  <span class="ml-1">→</span>
                </Link>
                <a :href="whatsappLink" target="_blank" rel="noopener noreferrer" class="luxe-btn luxe-btn-ghost">
                  WhatsApp concierge
                </a>
              </div>

              <div v-if="topCategories.length" class="mt-8 flex flex-wrap gap-2">
                <Link
                  v-for="c in topCategories"
                  :key="c.slug"
                  :href="route('category.show', { slug: c.slug })"
                  class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/5 px-4 py-2 text-sm font-semibold text-luxe-pearl/90 transition hover:bg-white/10"
                >
                  <span class="h-1.5 w-1.5 rounded-full bg-luxe-gold" aria-hidden="true" />
                  <span>{{ c.label }}</span>
                </Link>
              </div>
            </div>
          </div>

          <!-- Right: "product lens" frame (uses current slide) -->
          <div class="lg:col-span-6">
            <div class="relative mx-auto max-w-xl">
              <div class="pointer-events-none absolute -inset-10 rounded-[3rem] bg-[radial-gradient(closest-side,rgba(199,164,93,0.22),transparent_70%)] blur-2xl" aria-hidden="true" />

              <div class="relative overflow-hidden rounded-5xl border border-white/12 bg-black/30 shadow-luxe-lg backdrop-blur-2xl">
                <div class="absolute inset-0 bg-[radial-gradient(800px_480px_at_70%_20%,rgba(255,255,255,0.10),transparent_60%)]" aria-hidden="true" />
                <div class="p-4 sm:p-5">
                  <div class="flex items-center justify-between gap-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-luxe-mist">
                      Featured visual
                    </p>
                    <div class="flex items-center gap-1.5">
                      <span class="h-1.5 w-1.5 rounded-full bg-luxe-emerald" aria-hidden="true" />
                      <span class="text-xs font-semibold text-luxe-pearl/80">Live</span>
                    </div>
                  </div>

                  <div class="mt-4 overflow-hidden rounded-4xl border border-white/10 bg-black/40">
                    <img
                      v-if="currentSlide"
                      :src="currentSlide"
                      alt=""
                      class="h-[320px] w-full object-cover sm:h-[420px]"
                      loading="eager"
                    />
                    <div v-else class="flex h-[320px] items-center justify-center sm:h-[420px]">
                      <p class="text-sm text-luxe-mist/80">Add hero images in Admin to show visuals here.</p>
                    </div>
                  </div>

                  <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                      <p class="luxe-kicker">Secure checkout</p>
                      <p class="mt-2 text-sm text-luxe-pearl/80">Fast ordering, clear steps, premium experience.</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                      <p class="luxe-kicker">Premium support</p>
                      <p class="mt-2 text-sm text-luxe-pearl/80">Concierge help for scent, size, and routine picks.</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Subtle "scroll cue" -->
              <div class="mt-8 flex justify-center">
                <div class="h-10 w-6 rounded-full border border-white/15 bg-white/5 p-1">
                  <div class="h-2 w-2 animate-bounce rounded-full bg-luxe-gold" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide nav (only if multiple) -->
    <template v-if="slides.length > 1">
      <div class="absolute right-4 top-1/2 z-40 hidden -translate-y-1/2 flex-col gap-2 md:flex" role="tablist" aria-label="Slides">
        <button
          v-for="(_, i) in slides"
          :key="i"
          type="button"
          :aria-label="`Slide ${i + 1}`"
          :aria-selected="i === currentIndex"
          class="h-10 w-1.5 rounded-full transition-all duration-300"
          :class="i === currentIndex ? 'bg-luxe-gold' : 'bg-white/25 hover:bg-white/40'"
          @click="goTo(i)"
        />
      </div>
      <button
        type="button"
        class="absolute left-4 top-1/2 z-40 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-3xl border border-white/15 bg-white/5 text-white backdrop-blur transition hover:bg-white/10 md:flex md:left-8"
        aria-label="Previous"
        @click="prev"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
      </button>
      <button
        type="button"
        class="absolute right-4 top-1/2 z-40 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-3xl border border-white/15 bg-white/5 text-white backdrop-blur transition hover:bg-white/10 md:flex md:right-8"
        aria-label="Next"
        @click="next"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
      </button>
    </template>
  </section>
</template>
