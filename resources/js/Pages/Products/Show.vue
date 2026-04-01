<script setup>
import { ref, computed, inject, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { useJsonLd } from '@/composables/useJsonLd';
import AppLayout from '@/Layouts/AppLayout.vue';
import { storageUrl } from '@/utils/storageUrl';
import { ShoppingCart, Minus, Plus, ChevronRight, Truck, Package, MessageCircle, X, ShieldCheck, Sparkles, Droplets } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Product' },
  product: { type: Object, required: true },
});

const price = computed(() => Number(props.product?.price) ?? 0);
const hasPrice = computed(() => price.value > 0);

function normalizeName(s) {
  return String(s || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
}
const productKind = computed(() => {
  const cat = props.product?.category?.name ?? '';
  const s = normalizeName(cat + ' ' + (props.product?.name ?? ''));
  if (s.includes('watch')) return 'watch';
  if (s.includes('perfume') || s.includes('fragrance') || s.includes('eau de')) return 'perfume';
  if (s.includes('serum') || s.includes('skincare') || s.includes('skin')) return 'serum';
  return 'general';
});

const openCartDrawer = inject('openCartDrawer', null);
const quantity = ref(1);
const addingToCart = ref(false);
function addToCart() {
  const productId = Number(props.product?.id);
  if (!productId || addingToCart.value) return;
  addingToCart.value = true;
  router.post(route('cart.add'), {
    product_id: productId,
    quantity: clampQty(quantity.value),
  }, {
    preserveScroll: true,
    onFinish: () => { addingToCart.value = false; },
    onSuccess: () => { openCartDrawer?.(); },
  });
}
function clampQty(n) {
  return Math.max(1, Math.min(99, n));
}

function formatPrice(value) {
  return new Intl.NumberFormat('en-ZM', { style: 'currency', currency: 'ZMW', minimumFractionDigits: 2 }).format(value);
}

const page = usePage();
const zuaaz = computed(() => page.props.zuaaz || {});
const whatsappViewingUrl = computed(() => {
  const num = (zuaaz.value?.whatsapp?.primary || '+260970000000').replace(/\D/g, '');
  const text = encodeURIComponent(`Hi, I'd like to arrange viewing/collection for: ${props.product?.name || 'this product'}`);
  return `https://wa.me/${num}?text=${text}`;
});
const appUrl = computed(() => page.props.appUrl || '');
const canonicalUrl = computed(() => `${appUrl.value}/products/${props.product.slug}`);

// Prefer backend-provided full URLs (image_url, gallery_urls); fall back to storage paths
const allImages = computed(() => {
  const urls = [];
  if (props.product.image_url) urls.push(props.product.image_url);
  else if (props.product.image) urls.push(storageUrl(props.product.image));
  if (Array.isArray(props.product.gallery_urls) && props.product.gallery_urls.length) {
    urls.push(...props.product.gallery_urls);
  } else if (Array.isArray(props.product.gallery) && props.product.gallery.length) {
    props.product.gallery.forEach((path) => urls.push(storageUrl(path)));
  }
  return urls;
});

const selectedIndex = ref(0);
const mainImageUrl = computed(() => allImages.value[selectedIndex.value] || null);

const hasImages = computed(() => allImages.value.length > 0);

const activeInfoTab = ref('overview'); // overview | details | care
const infoTabs = computed(() => {
  const base = [
    { key: 'overview', label: 'Overview' },
    { key: 'details', label: productKind.value === 'watch' ? 'Specs' : productKind.value === 'perfume' ? 'Notes' : productKind.value === 'serum' ? 'Ingredients' : 'Details' },
    { key: 'care', label: productKind.value === 'serum' ? 'Routine' : 'Shipping & returns' },
  ];
  return base;
});

const imageModalOpen = ref(false);
function openImageModal() {
  if (hasImages.value) imageModalOpen.value = true;
}
function closeImageModal() {
  imageModalOpen.value = false;
}
function onKeydown(e) {
  if (e.key === 'Escape') closeImageModal();
}
onMounted(() => {
  window.addEventListener('keydown', onKeydown);
});
onUnmounted(() => {
  window.removeEventListener('keydown', onKeydown);
});

// Thumbnail carousel: show a sliding window, no scrollbar
const THUMB_VISIBLE = 4;
const thumbCarouselOffset = ref(0);
const thumbItemPx = 88; // w-20 (80px) + gap-2 (8px)
function thumbPrev() {
  thumbCarouselOffset.value = Math.max(0, thumbCarouselOffset.value - 1);
}
function thumbNext() {
  const maxOffset = Math.max(0, allImages.value.length - THUMB_VISIBLE);
  thumbCarouselOffset.value = Math.min(maxOffset, thumbCarouselOffset.value + 1);
}
function thumbCarouselTranslate() {
  return `translateX(-${thumbCarouselOffset.value * thumbItemPx}px)`;
}
const canThumbPrev = computed(() => thumbCarouselOffset.value > 0);
const canThumbNext = computed(() => thumbCarouselOffset.value < Math.max(0, allImages.value.length - THUMB_VISIBLE));
// Keep selected thumbnail in view when clicking a thumb
function onThumbClick(i) {
  selectedIndex.value = i;
  if (i < thumbCarouselOffset.value) thumbCarouselOffset.value = i;
  else if (i >= thumbCarouselOffset.value + THUMB_VISIBLE) thumbCarouselOffset.value = i - THUMB_VISIBLE + 1;
}

function absoluteUrl(url) {
  if (!url) return url;
  return url.startsWith('http') ? url : `${appUrl.value}${url.startsWith('/') ? url : '/' + url}`;
}

useJsonLd(() => {
  const productSchema = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: props.product.name,
    description: (props.product.short_description || props.product.description || props.product.name || '').slice(0, 500),
    url: canonicalUrl.value,
  };
  if (allImages.value.length) {
    productSchema.image = allImages.value.map(absoluteUrl);
  }
  const breadcrumb = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: [
      { '@type': 'ListItem', position: 1, name: 'Home', item: appUrl.value },
      { '@type': 'ListItem', position: 2, name: 'Products', item: `${appUrl.value}/products` },
      { '@type': 'ListItem', position: 3, name: props.product.name, item: canonicalUrl.value },
    ],
  };
  return [productSchema, breadcrumb];
});
</script>

<template>
  <AppLayout>
    <SeoHead
      :title="product.name + ' | SK Traders Zambia'"
      :description="(product.short_description || product.description || product.name + ' – premium selection from SK Traders.').slice(0, 160)"
      :canonical="canonicalUrl"
      :image="product.image_url || undefined"
    />

    <section class="relative bg-luxe-obsidian">
      <!-- Luxe top strip -->
      <div class="border-b border-white/10 bg-black/40">
        <div class="luxe-container py-8">
          <nav class="flex min-w-0 flex-wrap items-center gap-2 text-sm text-luxe-mist" aria-label="Breadcrumb">
            <Link :href="route('products')" class="inline-flex items-center gap-1 font-medium transition-colors hover:text-luxe-gold">
              Products
              <ChevronRight class="h-4 w-4 shrink-0 opacity-60" stroke-width="2" />
            </Link>
            <span class="min-w-0 truncate font-medium text-luxe-pearl" aria-hidden="true">{{ product.name }}</span>
          </nav>
          <p class="luxe-kicker mt-4">Product details</p>
        </div>
      </div>

      <div class="relative luxe-container py-10 lg:py-14">
        <div class="grid min-w-0 gap-8 lg:grid-cols-12 lg:gap-12 lg:items-start">
          <!-- Left: Image gallery – sticky -->
          <aside
            class="product-gallery-column lg:col-span-6 xl:col-span-5"
            aria-label="Product images"
          >
            <div class="product-gallery-sticky space-y-5">
              <!-- Main image – framed with shadow, click to open modal -->
              <div
                class="product-main-image group relative aspect-square w-full overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-luxe backdrop-blur-xl"
                :class="{ 'flex items-center justify-center': !hasImages }"
              >
                <template v-if="hasImages">
                  <button
                    type="button"
                    class="absolute inset-0 flex h-full w-full items-center justify-center focus:outline-none focus:ring-2 focus:ring-luxe-gold/70 focus:ring-inset"
                    aria-label="View full size image"
                    @click="openImageModal"
                  >
                    <img
                      :src="mainImageUrl"
                      :alt="product.name"
                      class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03] pointer-events-none"
                    />
                  </button>
                </template>
                <template v-else>
                  <div class="flex flex-col items-center justify-center gap-3 text-luxe-mist">
                    <svg class="h-24 w-24 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">No image</span>
                  </div>
                </template>
              </div>
              <!-- Thumbnails carousel: sliding window, no scrollbar, arrows -->
              <div v-if="allImages.length > 1" class="relative flex items-center gap-2">
                <button
                  type="button"
                  class="flex h-20 w-10 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-pearl transition hover:bg-white/10 disabled:opacity-40"
                  :disabled="!canThumbPrev"
                  aria-label="Previous thumbnails"
                  @click="thumbPrev"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <div class="min-w-0 flex-1 overflow-hidden">
                  <div
                    class="flex gap-2 transition-transform duration-300 ease-out"
                    :style="{ transform: thumbCarouselTranslate() }"
                  >
                    <button
                      v-for="(url, i) in allImages"
                      :key="i"
                      type="button"
                      class="relative h-20 w-20 shrink-0 overflow-hidden rounded-3xl border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-luxe-gold/70 focus:ring-offset-2 focus:ring-offset-black"
                      :class="selectedIndex === i
                        ? 'border-luxe-gold/60 shadow-luxe'
                        : 'border-white/10 hover:border-luxe-gold/30'"
                      @click="onThumbClick(i)"
                    >
                      <img
                        :src="url"
                        :alt="`${product.name} – image ${i + 1}`"
                        class="h-full w-full object-cover"
                      />
                    </button>
                  </div>
                </div>
                <button
                  type="button"
                  class="flex h-20 w-10 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-pearl transition hover:bg-white/10 disabled:opacity-40"
                  :disabled="!canThumbNext"
                  aria-label="Next thumbnails"
                  @click="thumbNext"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </aside>

          <!-- Right: Product details -->
          <div class="product-details-column min-w-0 lg:col-span-6 xl:col-span-7">
            <div class="lg:sticky lg:top-24">
              <!-- Buy box -->
              <div class="luxe-surface-strong rounded-3xl p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-3">
                  <span v-if="product.category" class="inline-flex items-center gap-2 rounded-3xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-luxe-mist">
                    <Sparkles class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                    {{ product.category.name }}
                  </span>
                  <span class="inline-flex items-center gap-2 rounded-3xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-luxe-mist">
                    <ShieldCheck class="h-4 w-4 text-luxe-gold" stroke-width="2" />
                    Premium selection
                  </span>
                </div>

                <h1 class="luxe-title mt-5 break-words text-3xl sm:text-4xl">
                  {{ product.name }}
                </h1>
                <p v-if="product.short_description" class="mt-4 text-base leading-relaxed text-luxe-pearl/80">
                  {{ product.short_description }}
                </p>

                <div v-if="hasPrice" class="mt-6 flex flex-wrap items-baseline gap-3 border-y border-white/10 py-5">
                  <span class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">Price</span>
                  <span class="font-display text-2xl font-semibold tracking-tight text-luxe-pearl sm:text-3xl">{{ formatPrice(price) }}</span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-[auto_1fr] sm:items-center">
                  <div class="product-qty flex items-center justify-between rounded-3xl border border-white/10 bg-black/30">
                    <button type="button" class="h-12 w-12 text-luxe-pearl transition hover:bg-white/5" aria-label="Decrease quantity" @click="quantity = clampQty(quantity - 1)">
                      <Minus class="mx-auto h-4 w-4" stroke-width="2.5" />
                    </button>
                    <input
                      v-model.number="quantity"
                      type="number"
                      min="1"
                      max="99"
                      class="w-16 border-0 bg-transparent text-center text-base font-semibold text-luxe-pearl [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                      @change="quantity = clampQty(quantity)"
                    />
                    <button type="button" class="h-12 w-12 text-luxe-pearl transition hover:bg-white/5" aria-label="Increase quantity" @click="quantity = clampQty(quantity + 1)">
                      <Plus class="mx-auto h-4 w-4" stroke-width="2.5" />
                    </button>
                  </div>
                  <button type="button" :disabled="addingToCart" class="luxe-btn luxe-btn-primary w-full" @click="addToCart()">
                    <ShoppingCart class="h-5 w-5 shrink-0" stroke-width="2" />
                    {{ addingToCart ? 'Adding…' : 'Add to cart' }}
                  </button>
                </div>

                <div class="mt-6 grid gap-3">
                  <div class="luxe-surface rounded-3xl p-4">
                    <div class="flex items-start gap-3">
                      <span class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                        <Truck class="h-5 w-5" stroke-width="2" />
                      </span>
                      <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">Delivery</p>
                        <p class="mt-1 text-sm leading-snug text-luxe-pearl/80">
                          {{ hasPrice ? 'Tracked delivery where available.' : 'Get pricing and availability within 24 hours.' }}
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="luxe-surface rounded-3xl p-4">
                    <div class="flex items-start gap-3">
                      <span class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold">
                        <MessageCircle class="h-5 w-5" stroke-width="2" />
                      </span>
                      <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">Concierge</p>
                        <p class="mt-1 text-sm leading-snug text-luxe-pearl/80">Ask questions or request recommendations.</p>
                      </div>
                      <a :href="whatsappViewingUrl" target="_blank" rel="noopener noreferrer" class="luxe-btn luxe-btn-ghost shrink-0">
                        WhatsApp
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Structured info -->
              <div class="mt-6 luxe-surface rounded-3xl p-6 sm:p-8">
                <div class="flex flex-wrap gap-2">
                  <button
                    v-for="t in infoTabs"
                    :key="t.key"
                    type="button"
                    class="rounded-3xl border px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] transition"
                    :class="activeInfoTab === t.key ? 'border-luxe-gold/40 bg-luxe-gold text-black' : 'border-white/10 bg-white/5 text-luxe-mist hover:bg-white/10 hover:text-luxe-pearl'"
                    @click="activeInfoTab = t.key"
                  >
                    {{ t.label }}
                  </button>
                </div>

                <div class="mt-6">
                  <div v-show="activeInfoTab === 'overview'" class="space-y-4">
                    <p class="text-sm leading-relaxed text-luxe-pearl/80">
                      {{ product.short_description || 'A premium item selected by SK Traders.' }}
                    </p>
                    <div class="grid gap-3 sm:grid-cols-2">
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Category</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">{{ product.category?.name || '—' }}</p>
                      </div>
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Type</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">
                          {{ productKind === 'watch' ? 'Watch' : productKind === 'perfume' ? 'Perfume' : productKind === 'serum' ? 'Skincare serum' : 'Product' }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <div v-show="activeInfoTab === 'details'" class="space-y-4">
                    <div v-if="productKind === 'perfume'" class="grid gap-3 sm:grid-cols-2">
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Top notes</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Citrus, fresh, aromatic (if specified).</p>
                      </div>
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Base</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Amber, woods, musk (if specified).</p>
                      </div>
                    </div>
                    <div v-else-if="productKind === 'watch'" class="grid gap-3 sm:grid-cols-2">
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Materials</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Case, strap, and finish details (if specified).</p>
                      </div>
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Warranty</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Warranty information (if specified).</p>
                      </div>
                    </div>
                    <div v-else-if="productKind === 'serum'" class="grid gap-3 sm:grid-cols-2">
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Key ingredients</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Actives and concentrations (if specified).</p>
                      </div>
                      <div class="luxe-surface rounded-3xl p-4">
                        <p class="luxe-kicker">Skin type</p>
                        <p class="mt-2 text-sm text-luxe-pearl/85">Recommended skin types (if specified).</p>
                      </div>
                    </div>

                    <div v-if="product.description" class="luxe-surface rounded-3xl p-5">
                      <div class="flex items-center gap-2 text-luxe-gold">
                        <Droplets v-if="productKind === 'serum'" class="h-4 w-4" stroke-width="2" />
                        <Package v-else class="h-4 w-4" stroke-width="2" />
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">Full description</p>
                      </div>
                      <div class="product-description-content mt-4 max-w-none" v-html="product.description" />
                    </div>
                  </div>

                  <div v-show="activeInfoTab === 'care'" class="space-y-3">
                    <div class="luxe-surface rounded-3xl p-5">
                      <p class="luxe-kicker">Shipping</p>
                      <p class="mt-2 text-sm leading-relaxed text-luxe-pearl/80">We aim to dispatch quickly. Delivery times vary by location.</p>
                    </div>
                    <div class="luxe-surface rounded-3xl p-5">
                      <p class="luxe-kicker">Returns</p>
                      <p class="mt-2 text-sm leading-relaxed text-luxe-pearl/80">If there’s an issue, contact us and we’ll make it right in line with our policy.</p>
                    </div>
                    <div v-if="productKind === 'serum'" class="luxe-surface rounded-3xl p-5">
                      <p class="luxe-kicker">Routine</p>
                      <p class="mt-2 text-sm leading-relaxed text-luxe-pearl/80">Apply as directed. Patch-test and discontinue use if irritation occurs.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Image lightbox modal -->
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
          v-if="imageModalOpen"
          class="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4 backdrop-blur-sm"
          role="dialog"
          aria-modal="true"
          aria-label="Product image full size"
          @click.self="closeImageModal"
        >
          <button
            type="button"
            class="fixed right-4 top-4 z-[101] flex h-11 w-11 items-center justify-center rounded-3xl border border-white/10 bg-white/10 text-luxe-pearl shadow-luxe backdrop-blur-xl transition hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-luxe-gold/70"
            aria-label="Close"
            @click="closeImageModal"
          >
            <X class="h-5 w-5" stroke-width="2.5" />
          </button>
          <div class="relative max-h-[90vh] max-w-4xl">
            <img
              v-if="mainImageUrl"
              :src="mainImageUrl"
              :alt="product.name"
              class="max-h-[90vh] w-auto max-w-full object-contain shadow-2xl"
              @click.stop
            />
          </div>
        </div>
      </Transition>
    </Teleport>
  </AppLayout>
</template>

<style scoped>
/* Full Description – modern, premium styling for editor content */
.product-description-content {
  font-size: 0.9375rem;
  letter-spacing: 0.01em;
  color: rgba(233, 238, 247, 0.86);
}
.product-description-content :deep(p) {
  line-height: 1.75;
  margin-bottom: 0.875rem;
}
.product-description-content :deep(p:last-child) {
  margin-bottom: 0;
}

/* Headings – clear hierarchy, premium look */
.product-description-content :deep(h1),
.product-description-content :deep(h2),
.product-description-content :deep(h3),
.product-description-content :deep(h4),
.product-description-content :deep(h5),
.product-description-content :deep(h6) {
  color: rgba(247, 244, 239, 0.95);
  font-weight: 600;
  letter-spacing: -0.01em;
  margin-top: 2rem;
  margin-bottom: 0.75rem;
  line-height: 1.3;
}
.product-description-content :deep(h1) { font-size: 1.375rem; }
.product-description-content :deep(h2) {
  font-size: 1.25rem;
  border-left: 3px solid rgba(199, 164, 93, 0.7);
  padding-left: 1rem;
  padding-top: 0.125rem;
  padding-bottom: 0.125rem;
}
.product-description-content :deep(h3) {
  font-size: 1.0625rem;
  text-transform: uppercase;
  letter-spacing: 0.14em;
  color: rgba(199, 164, 93, 0.9);
}
.product-description-content :deep(h4) {
  font-size: 1rem;
  font-weight: 700;
  color: rgba(170, 179, 197, 0.92);
}
.product-description-content :deep(h5) { font-size: 0.9375rem; }
.product-description-content :deep(h6) { font-size: 0.875rem; color: rgba(170, 179, 197, 0.9); }
.product-description-content :deep(* + h1),
.product-description-content :deep(* + h2),
.product-description-content :deep(* + h3),
.product-description-content :deep(* + h4),
.product-description-content :deep(* + h5),
.product-description-content :deep(* + h6) {
  margin-top: 2.25rem;
}
.product-description-content :deep(h1:first-child),
.product-description-content :deep(h2:first-child),
.product-description-content :deep(h3:first-child),
.product-description-content :deep(h4:first-child) {
  margin-top: 0;
}

/* Bullet lists – premium card with custom markers */
.product-description-content :deep(ul),
.product-description-content :deep(ol) {
  margin-top: 1.25rem;
  margin-bottom: 1.25rem;
  padding-left: 0;
  list-style: none;
}
.product-description-content :deep(ul) {
  border-radius: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.10);
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem 1.25rem;
}
.product-description-content :deep(ul li) {
  position: relative;
  padding: 0.375rem 0 0.375rem 1.5rem;
  color: rgba(170, 179, 197, 0.92);
  line-height: 1.6;
}
.product-description-content :deep(ul li::before) {
  content: '';
  position: absolute;
  left: 0;
  top: 0.65em;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(199,164,93,1) 0%, rgba(215,166,166,1) 100%);
  box-shadow: 0 0 0 2px rgba(199, 164, 93, 0.18);
}
.product-description-content :deep(ol) {
  list-style: decimal;
  border-radius: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.10);
  background: rgba(255, 255, 255, 0.05);
  padding: 1rem 1.25rem 1rem 2.25rem;
}
.product-description-content :deep(ol li) {
  padding: 0.375rem 0;
  color: rgba(170, 179, 197, 0.92);
  line-height: 1.6;
}
.product-description-content :deep(li + li) {
  margin-top: 0.25rem;
}

/* Inline emphasis */
.product-description-content :deep(strong),
.product-description-content :deep(b) {
  font-weight: 600;
  color: rgba(247, 244, 239, 0.95);
}
.product-description-content :deep(em),
.product-description-content :deep(i) {
  font-style: italic;
  color: rgba(170, 179, 197, 0.92);
}
.product-description-content :deep(a) {
  font-weight: 600;
  color: rgba(199, 164, 93, 0.95);
  text-decoration: underline;
  text-underline-offset: 2px;
}

/* Blockquote if editor outputs it */
.product-description-content :deep(blockquote) {
  border-left: 3px solid rgba(199, 164, 93, 0.7);
  background: rgba(255, 255, 255, 0.05);
  padding: 0.75rem 1rem;
  border-radius: 0 0.75rem 0.75rem 0;
  font-style: italic;
  color: rgba(170, 179, 197, 0.92);
  margin: 1.25rem 0;
}

/* Sticky gallery on desktop */
@media (min-width: 1024px) {
  .product-gallery-sticky {
    position: sticky;
    top: 6rem;
  }
}
</style>
