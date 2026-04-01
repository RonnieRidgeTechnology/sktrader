<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Quote, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
  theme: { type: String, default: '' },
  title: { type: String, default: 'What Our Partners Say' },
  subtitle: { type: String, default: 'Trusted by fitness brands and retailers worldwide.' },
  testimonials: {
    type: Array,
    default: () => [],
  },
});

const isEditorial = computed(() => props.theme === 'editorial');
const current = ref(0);
const autoplayMs = 6000;
let autoplayTimer = null;

const list = computed(() => {
  const t = props.testimonials;
  return Array.isArray(t) && t.length ? t : [];
});

const total = computed(() => list.value.length);

const canPrev = computed(() => total.value > 1);
const canNext = computed(() => total.value > 1);

function goPrev() {
  if (!canPrev.value) return;
  current.value = current.value <= 0 ? total.value - 1 : current.value - 1;
  resetAutoplay();
}

function goNext() {
  if (!canNext.value) return;
  current.value = current.value >= total.value - 1 ? 0 : current.value + 1;
  resetAutoplay();
}

function goTo(i) {
  if (i >= 0 && i < total.value) {
    current.value = i;
    resetAutoplay();
  }
}

function resetAutoplay() {
  if (autoplayTimer) clearInterval(autoplayTimer);
  if (total.value > 1) {
    autoplayTimer = setInterval(goNext, autoplayMs);
  }
}

const slideStyle = computed(() => ({
  transform: `translateX(-${current.value * 100}%)`,
}));

onMounted(() => {
  if (total.value > 1) {
    autoplayTimer = setInterval(goNext, autoplayMs);
  }
});

onUnmounted(() => {
  if (autoplayTimer) clearInterval(autoplayTimer);
});
</script>

<template>
  <section
    class="relative min-w-0 overflow-hidden border-t px-4 py-10 sm:px-6 sm:py-16 lg:px-8 lg:py-28"
    :class="isEditorial ? 'border-editorial-ink/10 bg-editorial-ink' : 'border-zinc-200/80 bg-zinc-950 dark:border-zinc-800'"
  >
    <div class="pointer-events-none absolute inset-0" aria-hidden="true" :class="isEditorial ? 'bg-gradient-to-b from-editorial-ink/80 to-transparent' : 'bg-gradient-to-b from-zinc-900/50 to-transparent'" />
    <div class="relative mx-auto min-w-0 max-w-6xl">
      <p class="text-center text-xs font-semibold uppercase tracking-[0.2em] sm:tracking-[0.25em]" :class="isEditorial ? 'text-editorial-cream/70' : 'text-zinc-500'">
        Testimonials
      </p>
      <h2 class="mt-2 text-center text-2xl font-bold tracking-tight sm:mt-3 sm:text-3xl lg:text-4xl" :class="isEditorial ? 'font-editorial text-editorial-cream' : 'text-white'">
        {{ title }}
      </h2>
      <p class="mx-auto mt-2 max-w-2xl text-center text-sm sm:mt-3 sm:text-base" :class="isEditorial ? 'text-editorial-cream/80' : 'text-zinc-400'">
        {{ subtitle }}
      </p>

      <!-- Carousel: sliding strip; full width on mobile, arrows hidden on mobile -->
      <div v-if="list.length" class="mt-8 sm:mt-14 -mx-4 sm:mx-0">
        <div class="relative flex min-w-0 items-center justify-center gap-2 sm:gap-4 md:gap-8">
          <button
            v-if="canPrev"
            type="button"
            class="hidden h-11 w-11 shrink-0 items-center justify-center border transition focus:outline-none focus:ring-2 sm:flex sm:h-12 sm:w-12"
            :class="isEditorial ? 'rounded-sm border-editorial-cream/50 bg-editorial-cream/10 text-editorial-cream hover:bg-editorial-cream/20 focus:ring-editorial-coral' : 'rounded-full border-zinc-600 bg-zinc-800/80 text-white hover:border-zinc-500 hover:bg-zinc-700 focus:ring-white/30'"
            aria-label="Previous testimonial"
            @click="goPrev"
          >
            <ChevronLeft class="h-5 w-5 sm:h-6 sm:w-6" stroke-width="2" />
          </button>

          <div class="testimonial-viewport min-h-[260px] min-w-0 flex-1 overflow-hidden sm:min-h-[280px]">
            <div
              class="testimonial-track flex transition-transform duration-400 ease-out"
              :style="slideStyle"
            >
              <article
                v-for="(item, i) in list"
                :key="i"
                class="testimonial-slide shrink-0 w-full px-3 sm:px-2"
              >
                <div
                  class="mx-auto w-full max-w-2xl border-y p-5 shadow-2xl backdrop-blur sm:border sm:p-8 lg:p-10"
                  :class="isEditorial ? 'rounded-sm border-editorial-cream/20 bg-editorial-cream/10' : 'rounded-none border-zinc-700/80 bg-zinc-900/80 sm:rounded-3xl'"
                >
                  <Quote class="mx-auto mb-4 h-10 w-10 sm:mb-6 sm:h-12 sm:w-12" :class="isEditorial ? 'text-editorial-coral' : 'text-amber-500/60'" aria-hidden="true" />
                  <blockquote class="text-center text-base leading-relaxed sm:text-lg lg:text-xl" :class="isEditorial ? 'text-editorial-cream' : 'text-zinc-200'">
                    "{{ item.quote }}"
                  </blockquote>
                  <footer class="mt-6 flex flex-col items-center gap-2 border-t pt-4 sm:mt-8 sm:pt-6" :class="isEditorial ? 'border-editorial-cream/20' : 'border-zinc-700/80'">
                    <div
                      class="flex h-12 w-12 items-center justify-center text-base font-bold sm:h-14 sm:w-14 sm:text-lg"
                      :class="isEditorial ? 'rounded-sm bg-editorial-coral/30 text-editorial-coral' : 'rounded-full bg-amber-500/20 text-amber-400'"
                    >
                      {{ (item.name || 'A').charAt(0) }}
                    </div>
                    <p class="text-sm font-semibold sm:text-base" :class="isEditorial ? 'text-editorial-cream' : 'text-white'">{{ item.name }}</p>
                    <p class="text-xs sm:text-sm" :class="isEditorial ? 'text-editorial-cream/70' : 'text-zinc-500'">
                      {{ [item.role, item.company].filter(Boolean).join(' · ') || '' }}
                    </p>
                  </footer>
                </div>
              </article>
            </div>
          </div>

          <button
            v-if="canNext"
            type="button"
            class="hidden h-11 w-11 shrink-0 items-center justify-center border transition focus:outline-none focus:ring-2 sm:flex sm:h-12 sm:w-12"
            :class="isEditorial ? 'rounded-sm border-editorial-cream/50 bg-editorial-cream/10 text-editorial-cream hover:bg-editorial-cream/20 focus:ring-editorial-coral' : 'rounded-full border-zinc-600 bg-zinc-800/80 text-white hover:border-zinc-500 hover:bg-zinc-700 focus:ring-white/30'"
            aria-label="Next testimonial"
            @click="goNext"
          >
            <ChevronRight class="h-5 w-5 sm:h-6 sm:w-6" stroke-width="2" />
          </button>
        </div>

        <!-- Dots (min 44px touch targets) -->
        <div v-if="total > 1" class="mt-6 flex justify-center gap-3 sm:mt-8">
          <button
            v-for="(item, i) in list"
            :key="i"
            type="button"
            class="group flex h-11 w-11 min-h-[44px] min-w-[44px] items-center justify-center rounded-full transition-all duration-200 touch-manipulation"
            :aria-label="'Go to testimonial ' + (i + 1)"
            :aria-current="i === current ? 'true' : undefined"
            @click="goTo(i)"
          >
            <span
              class="block h-2.5 w-2.5 rounded-full transition-all duration-200"
              :class="[
                i === current ? 'scale-125' : '',
                i === current ? (isEditorial ? 'bg-editorial-coral' : 'bg-amber-500') : (isEditorial ? 'bg-editorial-cream/50 group-hover:bg-editorial-cream/70' : 'bg-zinc-600 group-hover:bg-zinc-500'),
              ]"
            />
          </button>
        </div>
      </div>

      <!-- Empty state -->
      <div
        v-else
        class="mt-8 rounded-2xl border border-dashed px-4 py-12 text-center sm:mt-14 sm:rounded-3xl sm:px-8 sm:py-16"
        :class="isEditorial ? 'border-editorial-cream/30 bg-editorial-cream/5' : 'border-zinc-700 bg-zinc-900/50'"
      >
        <Quote class="mx-auto h-10 w-10 sm:h-12 sm:w-12" :class="isEditorial ? 'text-editorial-coral/60' : 'text-zinc-600'" aria-hidden="true" />
        <p class="mt-3 text-sm sm:mt-4 sm:text-base" :class="isEditorial ? 'text-editorial-cream/70' : 'text-zinc-500'">Testimonials will appear here. Add them from the admin panel.</p>
      </div>
    </div>
  </section>
</template>

<style scoped>
.testimonial-viewport {
  width: 100%;
}
.testimonial-track {
  will-change: transform;
}
.testimonial-slide {
  flex: 0 0 100%;
  max-width: 100%;
}
</style>
