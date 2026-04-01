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
  <section class="reels-strip bg-luxe-obsidian" aria-label="Video reels">
    <div class="luxe-container py-16 sm:py-24">
      <div class="flex flex-col items-center justify-between gap-6 sm:flex-row sm:items-end">
        <div class="text-center sm:text-left">
          <p class="luxe-kicker">Reels</p>
          <h2 class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">A closer look</h2>
          <p class="mt-3 max-w-xl text-sm text-luxe-pearl/75 sm:text-base">
            Quick clips to preview finishes, textures, packaging, and what arrives at your door.
          </p>
        </div>
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist/80">Auto-scroll · Hover to pause</p>
      </div>
    <div
      ref="scrollRef"
      class="reels-scroll mt-10 flex snap-x snap-mandatory gap-4 overflow-x-auto pb-6 sm:gap-6"
      style="scrollbar-width: none;"
      @mouseenter="stopAutoSlide"
      @mouseleave="startAutoSlide"
    >
      <div class="mx-auto flex max-w-7xl flex-shrink-0 gap-4 sm:gap-6">
        <button
          v-for="(item, index) in videos"
          :key="index"
          type="button"
          class="reel-card group relative flex min-w-[280px] max-w-[340px] flex-shrink-0 snap-center flex-col overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-luxe transition hover:border-white/25 hover:bg-white/10 sm:min-w-[320px]"
          @click="openModal(item)"
        >
          <div class="relative w-full bg-[#1a1918] h-40 sm:h-44">
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
              <span class="flex h-16 w-16 items-center justify-center rounded-full border border-white/20 bg-black/40 text-white backdrop-blur">
                <Play class="h-8 w-8" fill="currentColor" stroke="none" />
              </span>
            </div>
          </div>
          <div v-if="item.title" class="border-t border-white/10 px-4 py-3">
            <p class="text-sm font-semibold text-luxe-pearl">{{ item.title }}</p>
          </div>
        </button>
      </div>
    </div>
    <p v-if="!videos || videos.length === 0" class="mt-10 text-center text-sm text-luxe-mist/80">
      Video reels will appear here.
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
              class="absolute -top-12 right-0 flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white backdrop-blur transition hover:bg-white/10"
              aria-label="Close"
              @click="closeModal"
            >
              <X class="h-6 w-6" />
            </button>
            <div class="aspect-video w-full overflow-hidden rounded-3xl border border-white/15 bg-black shadow-luxe-lg">
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
    </div>
  </section>
</template>

<style scoped>
.reels-scroll::-webkit-scrollbar {
  display: none;
}
</style>
