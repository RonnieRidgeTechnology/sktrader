<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';

/** Full-width premium slider: slides left, one new image from right. No dots. */
const props = defineProps({
  theme: { type: String, default: '' },
  images: { type: Array, default: () => [] },
});
const isEditorial = computed(() => props.theme === 'editorial');

const VISIBLE = 5;
const list = computed(() => (Array.isArray(props.images) ? props.images.filter(Boolean) : []));
const duplicated = computed(() => list.value.length > 0 ? [...list.value, ...list.value] : []);
const total = computed(() => duplicated.value.length);

const currentIndex = ref(0);
const isAnimating = ref(false);
const trackRef = ref(null);
const stepPx = ref(0);

function measureStep() {
  if (!trackRef.value || !list.value.length) return;
  const track = trackRef.value;
  const first = track.querySelector('.gallery-slide-item');
  if (!first) return;
  const style = getComputedStyle(track);
  const gap = parseFloat(style.gap) || 12;
  stepPx.value = first.offsetWidth + gap;
}

const trackStyle = computed(() => {
  const tx = stepPx.value ? -currentIndex.value * stepPx.value : 0;
  return {
    transform: `translate3d(${tx}px, 0, 0)`,
    transition: isAnimating.value ? 'transform 0.7s cubic-bezier(0.22, 1, 0.36, 1)' : 'none',
  };
});

const showPrev = computed(() => list.value.length > VISIBLE || list.value.length > 1);
const showNext = computed(() => list.value.length > VISIBLE || list.value.length > 1);

function goPrev() {
  if (isAnimating.value || !showPrev.value) return;
  isAnimating.value = true;
  currentIndex.value = currentIndex.value === 0 ? total.value / 2 - 1 : currentIndex.value - 1;
  setTimeout(() => { isAnimating.value = false; }, 720);
}

function goNext() {
  if (isAnimating.value || !showNext.value) return;
  isAnimating.value = true;
  currentIndex.value = currentIndex.value + 1;
  if (currentIndex.value >= total.value / 2) {
    setTimeout(() => {
      currentIndex.value = 0;
      isAnimating.value = false;
    }, 720);
  } else {
    setTimeout(() => { isAnimating.value = false; }, 720);
  }
}

let autoInterval = null;
let resizeObserver = null;
onMounted(() => {
  nextTick(() => {
    measureStep();
    resizeObserver = new ResizeObserver(measureStep);
    if (trackRef.value) resizeObserver.observe(trackRef.value);
  });
  if (list.value.length > 1) {
    autoInterval = setInterval(goNext, 5000);
  }
});
onUnmounted(() => {
  if (autoInterval) clearInterval(autoInterval);
  if (resizeObserver && trackRef.value) resizeObserver.disconnect();
});
watch(list, () => nextTick(measureStep), { immediate: false });
</script>

<template>
  <section
    v-if="list.length > 0"
    class="relative w-full overflow-hidden py-4 sm:py-6"
    :class="isEditorial ? 'bg-editorial-paper border-y-2 border-editorial-ink/10' : 'bg-zinc-950'"
    aria-label="Gallery"
  >
    <div class="relative mx-auto w-full max-w-[100vw]">
      <!-- Viewport -->
      <div class="relative overflow-hidden px-2 sm:px-4">
        <!-- Sliding track -->
        <div
          ref="trackRef"
          class="flex gap-2 sm:gap-3 md:gap-4"
          :style="trackStyle"
        >
          <template v-for="(url, i) in duplicated" :key="`${i}-${url}`">
            <div
              class="gallery-slide-item relative flex-shrink-0 flex-grow-0 overflow-hidden border transition-all duration-500 ease-out will-change-transform"
              :class="isEditorial
                ? 'rounded-sm border-editorial-ink/20 bg-white shadow-md ' + (i === currentIndex + VISIBLE - 1 ? 'ring-2 ring-editorial-coral' : '')
                : 'rounded-xl border-zinc-800/80 bg-zinc-900 shadow-2xl ring-1 ring-white/5 sm:rounded-2xl ' + (i === currentIndex + VISIBLE - 1 ? 'ring-amber-500/20' : '')"
              style="width: calc((100vw - 2rem - 2rem) / 5); min-width: calc((100vw - 2rem - 2rem) / 5);"
            >
              <div class="aspect-[4/3] w-full sm:aspect-[3/2]">
                <img
                  :src="url"
                  :alt="`Gallery image ${(i % list.length) + 1}`"
                  class="h-full w-full object-cover transition-transform duration-700 ease-out hover:scale-105"
                  loading="lazy"
                />
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- Prev -->
      <button
        v-if="showPrev"
        type="button"
        class="absolute left-2 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center backdrop-blur transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 sm:left-4"
        :class="isEditorial ? 'rounded-sm bg-editorial-ink/90 text-editorial-cream hover:bg-editorial-ink focus:ring-editorial-coral' : 'rounded-full bg-zinc-800/80 text-white shadow-xl hover:bg-zinc-700/90 focus:ring-amber-500'"
        aria-label="Previous slide"
        :disabled="isAnimating"
        @click="goPrev"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <!-- Next -->
      <button
        v-if="showNext"
        type="button"
        class="absolute right-2 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center backdrop-blur transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 sm:right-4"
        :class="isEditorial ? 'rounded-sm bg-editorial-ink/90 text-editorial-cream hover:bg-editorial-ink focus:ring-editorial-coral' : 'rounded-full bg-zinc-800/80 text-white shadow-xl hover:bg-zinc-700/90 focus:ring-amber-500'"
        aria-label="Next slide"
        :disabled="isAnimating"
        @click="goNext"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </section>
</template>

<style scoped>
.gallery-slide-item {
  backface-visibility: hidden;
}
</style>
