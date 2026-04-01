<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue';
import { Play, X } from 'lucide-vue-next';

const props = defineProps({
  videos: { type: Array, default: () => [] },
});

const selected = ref(null);
const modalVideoRef = ref(null);
const scrollRef = ref(null);

const AUTO_SLIDE_MS = 4000;
let autoSlideInterval = null;

function embedUrl(item, autoplay = false) {
  if (item?.youtubeId) {
    const base = `https://www.youtube.com/embed/${item.youtubeId}?rel=0`;
    return autoplay ? `${base}&autoplay=1` : base;
  }
  return null;
}
function openModal(item) { selected.value = item; }
function closeModal() {
  if (modalVideoRef.value?.tagName === 'VIDEO') modalVideoRef.value.pause();
  selected.value = null;
}
function onBackdropClick(e) { if (e.target === e.currentTarget) closeModal(); }

function onKeydown(e) {
  if (e.key === 'Escape' && selected.value) closeModal();
}

function advanceSlide() {
  const el = scrollRef.value;
  if (!el || props.videos.length <= 1) return;
  const card = el.querySelector('.reel-card');
  const gap = 16;
  const step = card ? card.offsetWidth + gap : el.clientWidth;
  const maxScroll = el.scrollWidth - el.clientWidth;
  if (maxScroll <= 0) return;
  let next = el.scrollLeft + step;
  if (next >= maxScroll) next = 0;
  el.scrollTo({ left: next, behavior: 'smooth' });
}

function startAutoSlide() {
  stopAutoSlide();
  if (props.videos.length <= 1) return;
  autoSlideInterval = setInterval(advanceSlide, AUTO_SLIDE_MS);
}
function stopAutoSlide() {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval);
    autoSlideInterval = null;
  }
}

watch(selected, (item) => {
  if (!item) return;
  setTimeout(() => {
    const el = modalVideoRef.value;
    if (el?.tagName === 'VIDEO') el.play().catch(() => {});
  }, 100);
});

watch(() => props.videos?.length, () => {
  nextTick(() => startAutoSlide());
}, { immediate: false });

onMounted(() => {
  window.addEventListener('keydown', onKeydown);
  nextTick(() => startAutoSlide());
});
onUnmounted(() => {
  window.removeEventListener('keydown', onKeydown);
  stopAutoSlide();
});
</script>

<template>
  <section class="reels-strip bg-[#0f0e0d] py-16 sm:py-24" aria-label="Showroom">
    <p class="mb-10 text-center text-xs font-medium uppercase tracking-[0.3em] text-white/60">
      Our showroom & collections
    </p>
    <div
      ref="scrollRef"
      class="reels-scroll flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-6 sm:gap-6 sm:px-6 md:px-8 lg:px-12"
      style="scrollbar-width: none;"
    >
      <div class="mx-auto flex max-w-7xl flex-shrink-0 gap-4 sm:gap-6">
        <button
          v-for="(item, index) in videos"
          :key="index"
          type="button"
          class="reel-card group relative flex min-w-[280px] max-w-[320px] flex-shrink-0 snap-center flex-col overflow-hidden border border-white/10 transition hover:border-white/25 sm:min-w-[320px]"
          @click="openModal(item)"
        >
          <div class="relative aspect-video w-full bg-[#1a1918]">
            <img
              v-if="item.youtubeId"
              :src="`https://img.youtube.com/vi/${item.youtubeId}/maxresdefault.jpg`"
              :alt="item.title || 'Video'"
              class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
              loading="lazy"
              @error="($event.target).src = `https://img.youtube.com/vi/${item.youtubeId}/hqdefault.jpg`"
            />
            <video
              v-else-if="item.videoUrl"
              :src="item.videoUrl"
              class="h-full w-full object-cover"
              preload="metadata"
              muted
              playsinline
            />
            <div v-else class="flex h-full w-full items-center justify-center text-white/40">
              <Play class="h-12 w-12" />
            </div>
            <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
              <span class="flex h-16 w-16 items-center justify-center border-2 border-white bg-white/90 text-[#0f0e0d]">
                <Play class="h-8 w-8" fill="currentColor" stroke="none" />
              </span>
            </div>
          </div>
          <div v-if="item.title" class="border-t border-white/10 bg-[#1a1918] px-4 py-3">
            <p class="text-sm font-medium text-white">{{ item.title }}</p>
          </div>
        </button>
      </div>
    </div>
    <p v-if="!videos || videos.length === 0" class="text-center text-sm text-white/40">
      Showroom videos will appear here.
    </p>

    <!-- Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="selected"
          class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4"
          role="dialog"
          aria-modal="true"
          @click="onBackdropClick"
        >
          <div class="relative w-full max-w-4xl" @click.stop>
            <button
              type="button"
              class="absolute -top-12 right-0 flex h-10 w-10 items-center justify-center text-white transition hover:text-white/80"
              aria-label="Close"
              @click="closeModal"
            >
              <X class="h-6 w-6" />
            </button>
            <div class="aspect-video w-full overflow-hidden border border-white/20 bg-black">
              <iframe
                v-if="embedUrl(selected)"
                :src="embedUrl(selected, true)"
                :title="selected.title || 'Video'"
                class="h-full w-full"
                allow="autoplay; encrypted-media"
                allowfullscreen
              />
              <video
                v-else-if="selected.videoUrl"
                ref="modalVideoRef"
                class="h-full w-full"
                :src="selected.videoUrl"
                controls
                playsinline
                autoplay
              />
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </section>
</template>

<style scoped>
.reels-scroll::-webkit-scrollbar {
  display: none;
}
</style>
