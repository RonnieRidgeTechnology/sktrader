<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Package, ChevronLeft, ChevronRight, CircleDollarSign, ShoppingCart } from 'lucide-vue-next';
import { storageUrl } from '@/utils/storageUrl';
import { inject } from 'vue';
import { useStoreCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  theme: { type: String, default: '' },
  products: { type: Array, default: () => [] },
  title: { type: String, default: 'Featured Products' },
  eyebrow: { type: String, default: 'Curated for you' },
  subtitle: { type: String, default: 'Handpicked watches, perfumes, and serums from our collection. Order online or chat with us on WhatsApp.' },
});

const isEditorial = computed(() => props.theme === 'editorial');
const isLuxe = computed(() => props.theme === 'luxe');
const viewportRef = ref(null);
const itemsPerPage = ref(4);
const currentIndex = ref(0);
const addingProductId = ref(null);
const openCartDrawer = inject('openCartDrawer', null);
const { formatMoney } = useStoreCurrency();

const list = computed(() => (Array.isArray(props.products) ? props.products : []));

const maxIndex = computed(() => {
  const n = list.value.length;
  const per = itemsPerPage.value;
  if (per <= 0 || n <= per) return 0;
  return n - per;
});

const canPrev = computed(() => currentIndex.value > 0);
const canNext = computed(() => currentIndex.value < maxIndex.value);

function getItemsPerPage(width) {
  if (width < 640) return 1;
  if (width < 768) return 2;
  return 4;
}

function goPrev() {
  if (canPrev.value) currentIndex.value--;
}

function goNext() {
  if (canNext.value) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0;
  }
}

function updateResponsive() {
  if (!viewportRef.value) return;
  const w = viewportRef.value.offsetWidth;
  itemsPerPage.value = getItemsPerPage(w);
  currentIndex.value = Math.min(currentIndex.value, maxIndex.value);
}

function formatPrice(value) {
  const n = Number(value);
  if (!Number.isFinite(n) || n <= 0) return null;
  return formatMoney(n);
}

/** Keep titles to ~2 lines: word cap + line-clamp-2 in template */
const TITLE_MAX_WORDS = 12;
function productTitleDisplay(name) {
  const s = String(name || '').trim();
  if (!s) return '';
  const words = s.split(/\s+/).filter(Boolean);
  if (words.length <= TITLE_MAX_WORDS) return s;
  return `${words.slice(0, TITLE_MAX_WORDS).join(' ')}…`;
}

function addToCart(productId, e) {
  if (e) e.preventDefault();
  if (e) e.stopPropagation();
  if (addingProductId.value !== null) return;
  addingProductId.value = productId;
  router.post(route('cart.add'), { product_id: productId, quantity: 1 }, {
    preserveScroll: true,
    onFinish: () => { addingProductId.value = null; },
    onSuccess: () => { openCartDrawer?.(); },
  });
}

const translatePercent = computed(() => {
  const per = itemsPerPage.value;
  if (per <= 0) return 0;
  return currentIndex.value * (100 / per);
});

const trackStyle = computed(() => ({
  transform: `translate3d(-${translatePercent.value}%, 0, 0)`,
}));

const trackWidth = computed(() => {
  const n = list.value.length;
  const per = itemsPerPage.value;
  if (per <= 0 || n === 0) return '100%';
  return `${(n / per) * 100}%`;
});

const itemWidth = computed(() => {
  const n = list.value.length;
  if (n === 0) return '100%';
  return `${100 / n}%`;
});

const AUTO_SLIDE_MS = 4000;
let resizeObserver = null;
let autoSlideTimer = null;

function startAutoSlide() {
  stopAutoSlide();
  autoSlideTimer = setInterval(() => {
    if (list.value.length <= 1) return;
    if (canNext.value) {
      currentIndex.value++;
    } else {
      currentIndex.value = 0;
    }
  }, AUTO_SLIDE_MS);
}

function stopAutoSlide() {
  if (autoSlideTimer) {
    clearInterval(autoSlideTimer);
    autoSlideTimer = null;
  }
}

onMounted(() => {
  nextTick(() => {
    updateResponsive();
    resizeObserver = new ResizeObserver(() => updateResponsive());
    if (viewportRef.value) resizeObserver.observe(viewportRef.value);
    startAutoSlide();
  });
});

onUnmounted(() => {
  stopAutoSlide();
  if (resizeObserver && viewportRef.value) {
    try {
      resizeObserver.unobserve(viewportRef.value);
    } catch (_) {}
  }
});
</script>

<template>
  <section
    class="relative overflow-hidden border-t py-[50px]"
    :class="isEditorial
      ? 'border-editorial-ink/10 bg-editorial-paper'
      : isLuxe
        ? 'border-white/10 bg-luxe-obsidian'
        : 'border-zinc-200/80 bg-gradient-to-b from-zinc-50 via-white to-zinc-50 dark:border-zinc-800 dark:from-zinc-900/50 dark:via-zinc-950 dark:to-zinc-900/50'"
    aria-labelledby="featured-products-heading"
  >
    <div v-if="isLuxe" class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
    <div class="relative mx-auto max-w-7xl px-4 pt-16 pb-6 sm:px-6 sm:pt-20 sm:pb-8 lg:px-8 lg:pt-24 lg:pb-8">
      <div class="mx-auto max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.25em]" :class="isEditorial ? 'text-editorial-coral' : isLuxe ? 'text-luxe-mist' : 'text-amber-600 dark:text-amber-400'">
          {{ eyebrow }}
        </p>
        <h2 id="featured-products-heading" class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl" :class="isEditorial ? 'font-editorial text-editorial-ink' : isLuxe ? 'font-display text-luxe-pearl' : 'text-zinc-900 dark:text-white'">
          {{ title }}
        </h2>
        <p class="mt-4 text-lg" :class="isEditorial ? 'text-editorial-slate' : isLuxe ? 'text-luxe-pearl/75' : 'text-zinc-600 dark:text-zinc-400'">
          {{ subtitle }}
        </p>
      </div>
    </div>

    <!-- Carousel: full width on mobile, padded on sm+ -->
    <div v-if="list.length" class="relative w-full px-0 pt-2 sm:px-[50px] sm:pt-4">
      <div class="relative flex items-stretch gap-2 sm:gap-4">
        <!-- Prev (hidden on mobile) -->
        <button
            type="button"
            :disabled="!canPrev"
            class="carousel-arrow order-1 hidden h-10 w-10 shrink-0 items-center justify-center border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-40 sm:flex sm:h-12 sm:w-12"
            :class="isEditorial
              ? 'rounded-sm border-editorial-ink/30 bg-editorial-cream text-editorial-ink hover:border-editorial-coral hover:bg-editorial-coral/10 focus:ring-editorial-coral focus:ring-offset-editorial-paper'
              : isLuxe
                ? 'rounded-3xl border-white/15 bg-white/5 text-luxe-pearl backdrop-blur hover:bg-white/10 focus:ring-luxe-gold/70 focus:ring-offset-black'
                : 'rounded-full border-zinc-200 bg-white text-zinc-700 shadow-lg shadow-zinc-200/50 hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700 hover:shadow-amber-200/40 focus:ring-amber-500 focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:shadow-zinc-950 dark:hover:border-amber-500/60 dark:hover:bg-amber-950/30 dark:hover:text-amber-400'"
            aria-label="Previous products"
            @click="goPrev"
          >
            <ChevronLeft class="h-5 w-5 sm:h-6 sm:w-6" stroke-width="2.5" />
          </button>

          <!-- Viewport: full width -->
          <div
            ref="viewportRef"
            class="featured-viewport order-2 min-w-0 flex-1 overflow-hidden"
          >
            <div
              class="featured-track flex items-stretch transition-transform duration-500 ease-out will-change-transform"
              :style="[trackStyle, { width: trackWidth }]"
            >
              <div
                v-for="product in list"
                :key="product.id"
                class="featured-item flex shrink-0 px-0 sm:px-2"
                :style="{ width: itemWidth }"
              >
                <Link
                  :href="route('products.show', product.slug)"
                  class="group relative flex h-full min-h-[280px] w-full flex-col overflow-hidden bg-white transition-all duration-300 sm:min-h-[300px]"
                  :class="isEditorial
                    ? 'rounded-sm border-2 border-editorial-ink/20 hover:-translate-y-1 hover:border-editorial-coral hover:shadow-lg'
                    : isLuxe
                      ? 'rounded-4xl border border-white/10 bg-white/5 shadow-luxe backdrop-blur hover:-translate-y-1 hover:bg-white/10'
                      : 'rounded-2xl shadow-lg shadow-zinc-200/60 ring-1 ring-zinc-200/80 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-300/50 hover:ring-amber-500/30 dark:bg-zinc-900/90 dark:shadow-zinc-950 dark:ring-zinc-700/80 dark:hover:shadow-2xl dark:hover:ring-amber-500/20'"
                >
                  <span
                    class="absolute right-3 top-3 z-10 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-sm"
                    :class="isEditorial ? 'rounded-sm bg-editorial-coral text-white' : isLuxe ? 'rounded-full bg-luxe-gold text-black' : 'rounded-full bg-amber-500 text-zinc-900'"
                  >
                    Featured
                  </span>
                  <div class="relative aspect-[4/3] w-full overflow-hidden" :class="isLuxe ? 'bg-black/40' : 'bg-zinc-100 dark:bg-zinc-800'">
                    <img
                      v-if="product.image_url || product.image"
                      :src="product.image_url || storageUrl(product.image)"
                      :alt="product.name"
                      loading="lazy"
                      class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                      @error="($event.target).style.display = 'none'"
                    />
                    <div
                      v-else
                      class="flex h-full w-full items-center justify-center text-zinc-400 dark:text-zinc-500"
                    >
                      <Package class="h-12 w-12 opacity-40 sm:h-16 sm:w-16" stroke-width="1.5" />
                    </div>
                    <div
                      class="absolute inset-0 bg-gradient-to-t from-zinc-900/40 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                      aria-hidden="true"
                    />
                  </div>
                  <div class="flex min-h-0 flex-1 flex-col px-4 pb-5 pt-4 sm:px-5 sm:pb-6 sm:pt-5">
                    <span
                      v-if="product.category"
                      class="text-[11px] font-semibold uppercase tracking-[0.2em]"
                      :class="isEditorial ? 'text-editorial-coral' : isLuxe ? 'text-luxe-mist' : 'text-amber-700 dark:text-amber-300'"
                    >
                      {{ product.category.parent ? `${product.category.parent.name} › ${product.category.name}` : product.category.name }}
                    </span>
                    <h3 class="mt-1.5 line-clamp-2 min-h-0 text-base font-semibold leading-snug tracking-tight sm:mt-2 sm:text-lg" :class="isEditorial ? 'font-editorial text-editorial-ink' : isLuxe ? 'text-luxe-pearl' : 'text-zinc-900 dark:text-white'">
                      {{ productTitleDisplay(product.name) }}
                    </h3>
                    <p
                      v-if="product.short_description"
                      class="mt-1.5 line-clamp-2 text-sm leading-relaxed sm:mt-2"
                      :class="isEditorial ? 'text-editorial-slate' : isLuxe ? 'text-luxe-pearl/75' : 'text-zinc-600 dark:text-zinc-400'"
                    >
                      {{ product.short_description }}
                    </p>

                    <div class="mt-auto pt-4">
                      <div class="flex items-end justify-between gap-2 sm:gap-3">
                        <div class="flex min-w-0 flex-1 items-start gap-1.5 sm:gap-2">
                          <span class="mt-0.5 shrink-0" :class="isEditorial ? 'text-editorial-coral' : isLuxe ? 'text-luxe-gold' : 'text-amber-600 dark:text-amber-400'" aria-hidden="true">
                            <CircleDollarSign class="h-4 w-4 sm:h-5 sm:w-5" stroke-width="2" />
                          </span>
                          <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] sm:text-xs sm:tracking-[0.22em]" :class="isEditorial ? 'text-editorial-slate' : isLuxe ? 'text-luxe-mist' : 'text-zinc-500 dark:text-zinc-400'">
                              Price
                            </p>
                            <p class="mt-0.5 break-words text-sm font-semibold leading-snug tabular-nums sm:text-base" :class="isEditorial ? 'text-editorial-ink' : isLuxe ? 'text-luxe-pearl' : 'text-zinc-900 dark:text-white'">
                              {{ formatPrice(product.price) || '—' }}
                            </p>
                          </div>
                        </div>

                        <button
                          type="button"
                          class="inline-flex shrink-0 items-center justify-center !min-h-9 !h-9 !gap-1 !rounded-xl !px-2.5 !py-0 !text-[11px] sm:!min-h-10 sm:!h-10 sm:!gap-1.5 sm:!px-3 sm:!text-xs"
                          :class="isLuxe ? 'luxe-btn luxe-btn-primary' : 'rounded-xl bg-black font-semibold text-white transition hover:bg-zinc-900'"
                          :aria-label="addingProductId === product.id ? 'Adding to cart' : 'Add to cart'"
                          :disabled="addingProductId === product.id"
                          @click="(e) => addToCart(product.id, e)"
                        >
                          <ShoppingCart class="h-3.5 w-3.5 shrink-0 sm:h-4 sm:w-4" stroke-width="2" />
                          <span v-if="addingProductId === product.id" class="hidden min-[360px]:inline">Adding…</span>
                          <span v-else class="hidden min-[360px]:inline">Add to cart</span>
                          <span v-if="addingProductId === product.id" class="inline min-[360px]:hidden">…</span>
                          <span v-else class="inline min-[360px]:hidden">Add</span>
                        </button>
                      </div>
                    </div>
                  </div>
                </Link>
              </div>
            </div>
          </div>

          <!-- Next (hidden on mobile) -->
          <button
            type="button"
            :disabled="!canNext"
            class="carousel-arrow order-3 hidden h-10 w-10 shrink-0 items-center justify-center border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-40 sm:flex sm:h-12 sm:w-12"
            :class="isEditorial
              ? 'rounded-sm border-editorial-ink/30 bg-editorial-cream text-editorial-ink hover:border-editorial-coral hover:bg-editorial-coral/10 focus:ring-editorial-coral focus:ring-offset-editorial-paper'
              : isLuxe
                ? 'rounded-3xl border-white/15 bg-white/5 text-luxe-pearl backdrop-blur hover:bg-white/10 focus:ring-luxe-gold/70 focus:ring-offset-black'
                : 'rounded-full border-zinc-200 bg-white text-zinc-700 shadow-lg shadow-zinc-200/50 hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700 hover:shadow-amber-200/40 focus:ring-amber-500 focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:shadow-zinc-950 dark:hover:border-amber-500/60 dark:hover:bg-amber-950/30 dark:hover:text-amber-400'"
            aria-label="Next products"
            @click="goNext"
          >
            <ChevronRight class="h-5 w-5 sm:h-6 sm:w-6" stroke-width="2.5" />
          </button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.featured-viewport {
  width: 100%;
}
.featured-track {
  align-items: stretch;
  min-height: 320px;
}
@media (min-width: 640px) {
  .featured-track {
    min-height: 360px;
  }
}
.featured-item {
  align-self: stretch;
  height: auto;
}
</style>
