<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Play, X } from 'lucide-vue-next';

/** Videos: { title, youtubeId? } or { title, embedCode? } or { title, videoUrl? } */
const props = defineProps({
  theme: { type: String, default: '' },
  title: { type: String, default: 'Our Showroom & Collections' },
  subtitle: { type: String, default: 'Take a look at our sofas and furniture — visit us in Lusaka or browse online.' },
  videos: {
    type: Array,
    default: () => [],
  },
});

const isEditorial = computed(() => props.theme === 'editorial');
const selected = ref(null);
const modalVideoRef = ref(null);

function embedUrl(item, autoplay = false) {
  if (item.youtubeId) {
    const base = `https://www.youtube.com/embed/${item.youtubeId}?rel=0`;
    return autoplay ? `${base}&autoplay=1` : base;
  }
  return null;
}

function openModal(item) {
  selected.value = item;
}

function closeModal() {
  selected.value = null;
}

function onBackdropClick(e) {
  if (e.target === e.currentTarget) closeModal();
}

watch(selected, (item) => {
  if (!item) return;
  // Auto-play HTML5 video when modal opens
  setTimeout(() => {
    const el = modalVideoRef.value;
    if (el && el.tagName === 'VIDEO') el.play().catch(() => {});
  }, 100);
});

function onModalClose() {
  const el = modalVideoRef.value;
  if (el && el.tagName === 'VIDEO') el.pause();
  closeModal();
}

function onKeydown(e) {
  if (e.key === 'Escape' && selected.value) onModalClose();
}

onMounted(() => window.addEventListener('keydown', onKeydown));
onUnmounted(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
  <section
    class="relative min-w-0 overflow-hidden border-t px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-24"
    :class="isEditorial ? 'border-editorial-ink/10 bg-editorial-paper' : 'border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/60'"
  >
    <div class="relative mx-auto min-w-0 max-w-7xl">
      <h2 class="text-center text-2xl font-bold tracking-tight sm:text-3xl lg:text-4xl" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">
        {{ title }}
      </h2>
      <p class="mx-auto mt-3 max-w-2xl text-center text-lg" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">
        {{ subtitle }}
      </p>

      <div class="mt-10 grid min-w-0 grid-cols-1 gap-4 sm:mt-14 sm:grid-cols-2 sm:gap-6 md:grid-cols-3 lg:grid-cols-5 lg:gap-6">
        <template v-for="(item, index) in videos" :key="index">
          <button
            type="button"
            class="group w-full cursor-pointer overflow-hidden border bg-white text-left shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-4"
            :class="isEditorial
              ? 'rounded-sm border-editorial-ink/20 hover:-translate-y-1 hover:border-editorial-coral hover:shadow-xl focus:ring-editorial-coral focus:ring-offset-editorial-paper'
              : 'rounded-3xl border-zinc-200 hover:-translate-y-2 hover:border-zinc-300 hover:shadow-2xl focus:ring-zinc-400 dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-zinc-600 dark:focus:ring-zinc-500'"
            @click="openModal(item)"
          >
            <div class="relative aspect-video w-full min-h-[280px] overflow-hidden bg-zinc-200 dark:bg-zinc-700 sm:min-h-[320px]">
              <!-- YouTube thumbnail image -->
              <img
                v-if="item.youtubeId"
                :src="`https://img.youtube.com/vi/${item.youtubeId}/maxresdefault.jpg`"
                :alt="(item.title ? item.title + ' – ' : '') + 'Gym gear and sportswear manufacturer factory video'"
                class="h-full w-full object-cover"
                loading="lazy"
                @error="($event.target).src = `https://img.youtube.com/vi/${item.youtubeId}/hqdefault.jpg`"
              />
              <!-- Raw embed thumbnail -->
              <div
                v-else-if="item.embedCode"
                class="reel-embed pointer-events-none h-full w-full [&>iframe]:h-full [&>iframe]:w-full"
                v-html="item.embedCode"
              />
              <!-- HTML5 video as thumbnail (no controls, preload metadata) -->
              <video
                v-else-if="item.videoUrl"
                :src="item.videoUrl"
                class="h-full w-full object-cover"
                preload="metadata"
                muted
                playsinline
                tabindex="-1"
                aria-hidden="true"
              />
              <!-- Placeholder when no source -->
              <div
                v-else
                class="flex h-full w-full flex-col items-center justify-center gap-2 text-zinc-500 dark:text-zinc-400"
              >
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-zinc-300 dark:bg-zinc-600">
                  <Play class="h-7 w-7 text-white" fill="currentColor" stroke="none" />
                </div>
                <span class="text-sm font-medium">Your factory reel</span>
              </div>
              <!-- Play overlay on thumbnail -->
              <div
                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 group-focus:opacity-100"
                aria-hidden="true"
              >
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-white/95 shadow-xl transition-transform group-hover:scale-110 dark:bg-zinc-800/95">
                  <Play class="h-10 w-10 text-zinc-900 dark:text-white" fill="currentColor" stroke="none" />
                </span>
              </div>
              <span class="absolute bottom-3 left-3 rounded-lg bg-black/60 px-2.5 py-1 text-xs font-medium text-white backdrop-blur-sm">Watch</span>
            </div>
            <div v-if="item.title" class="border-t px-5 py-4" :class="isEditorial ? 'border-editorial-ink/15' : 'border-zinc-100 dark:border-zinc-700'">
              <p class="text-base font-semibold" :class="isEditorial ? 'font-editorial text-editorial-ink' : 'text-zinc-900 dark:text-white'">{{ item.title }}</p>
            </div>
          </button>
        </template>

        <!-- Empty state: show 3 placeholder slots when no videos -->
        <template v-if="!videos || videos.length === 0">
          <div
            v-for="i in 3"
            :key="'placeholder-' + i"
            class="group flex min-h-[280px] aspect-video flex-col items-center justify-center overflow-hidden border border-dashed bg-white sm:min-h-[320px]"
            :class="isEditorial ? 'rounded-sm border-editorial-ink/30' : 'rounded-3xl border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800/50'"
          >
            <div class="flex h-16 w-16 items-center justify-center" :class="isEditorial ? 'rounded-sm bg-editorial-coral/20' : 'rounded-full bg-amber-100 dark:bg-amber-900/30'">
              <Play class="h-8 w-8" :class="isEditorial ? 'text-editorial-coral' : 'text-amber-600 dark:text-amber-400'" fill="currentColor" stroke="none" />
            </div>
            <p class="mt-3 text-sm font-medium" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-500 dark:text-zinc-400'">Factory reel {{ i }}</p>
            <p class="mt-1 text-xs" :class="isEditorial ? 'text-editorial-slate/70' : 'text-zinc-400 dark:text-zinc-500'">Add your manufacturing video</p>
          </div>
        </template>
      </div>
    </div>

    <!-- Video popup modal -->
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
          class="fixed inset-0 z-[100] flex items-end justify-center bg-black/90 p-0 sm:items-center sm:p-4"
          role="dialog"
          aria-modal="true"
          :aria-label="selected.title || 'Video'"
          @click="onBackdropClick"
        >
          <div
            class="relative w-full max-h-[85vh] min-h-0 overflow-hidden rounded-t-2xl bg-black shadow-2xl sm:max-h-[90vh] sm:max-w-4xl sm:rounded-2xl"
            @click.stop
          >
            <button
              type="button"
              class="absolute right-2 top-2 z-10 flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-white"
              aria-label="Close video"
              @click="onModalClose"
            >
              <X class="h-6 w-6" />
            </button>
            <div class="aspect-video w-full">
              <template v-if="embedUrl(selected)">
                <iframe
                  :src="embedUrl(selected, true)"
                  :title="selected.title || 'Factory reel'"
                  class="h-full w-full"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                />
              </template>
              <div
                v-else-if="selected.embedCode"
                class="reel-embed h-full w-full [&>iframe]:h-full [&>iframe]:w-full"
                v-html="selected.embedCode"
              />
              <video
                v-else-if="selected.videoUrl"
                ref="modalVideoRef"
                :src="selected.videoUrl"
                class="h-full w-full"
                controls
                playsinline
                autoplay
              >
                Your browser does not support the video tag.
              </video>
            </div>
            <div v-if="selected.title" class="border-t border-zinc-700 bg-zinc-900 px-4 py-3">
              <p class="text-sm font-medium text-white">{{ selected.title }}</p>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </section>
</template>
