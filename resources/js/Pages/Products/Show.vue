<script setup>
import { ref, computed, inject, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { useJsonLd } from '@/composables/useJsonLd';
import AppLayout from '@/Layouts/AppLayout.vue';
import { storageUrl } from '@/utils/storageUrl';
import { ShoppingCart, Minus, Plus, ChevronRight, Truck, Package, MessageCircle, X } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Product' },
  product: { type: Object, required: true },
});

const price = computed(() => Number(props.product?.price) ?? 0);
const hasPrice = computed(() => price.value > 0);

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
      :description="(product.short_description || product.description || product.name + ' – quality furniture from SK Traders, Zambia.').slice(0, 160)"
      :canonical="canonicalUrl"
      :image="product.image_url || undefined"
    />

    <section class="product-detail-section relative border-t border-editorial-ink/10 bg-gradient-to-b from-white to-editorial-cream">
      <!-- Top strip: breadcrumb + eyebrow -->
      <div class="border-b border-editorial-ink/10 bg-white/70 px-3 py-4 backdrop-blur-sm sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <nav class="flex min-w-0 flex-wrap items-center gap-2 text-sm" aria-label="Breadcrumb">
            <Link
              :href="route('products')"
              class="inline-flex items-center gap-1 font-medium text-editorial-slate transition-colors hover:text-editorial-coral"
            >
              Products
              <ChevronRight class="h-4 w-4 shrink-0 opacity-60" stroke-width="2" />
            </Link>
            <span class="min-w-0 truncate font-medium text-editorial-coral" aria-hidden="true">{{ product.name }}</span>
          </nav>
          <p class="mt-1 text-xs font-semibold uppercase tracking-[0.25em] text-editorial-slate/70">Product details</p>
        </div>
      </div>

      <div class="relative mx-auto min-w-0 max-w-7xl px-3 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-14">
        <div class="grid min-w-0 gap-8 lg:grid-cols-12 lg:gap-16 lg:items-start">
          <!-- Left: Image gallery – sticky -->
          <aside
            class="product-gallery-column lg:col-span-6 xl:col-span-5"
            aria-label="Product images"
          >
            <div class="product-gallery-sticky space-y-5">
              <!-- Main image – framed with shadow, click to open modal -->
              <div
                class="product-main-image group relative aspect-square w-full overflow-hidden border-2 border-editorial-ink/12 bg-editorial-paper shadow-product-image"
                :class="{ 'flex items-center justify-center': !hasImages }"
              >
                <template v-if="hasImages">
                  <button
                    type="button"
                    class="absolute inset-0 flex h-full w-full items-center justify-center focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-inset"
                    aria-label="View full size image"
                    @click="openImageModal"
                  >
                    <img
                      :src="mainImageUrl"
                      :alt="product.name"
                      class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.02] pointer-events-none"
                    />
                  </button>
                </template>
                <template v-else>
                  <div class="flex flex-col items-center justify-center gap-3 text-zinc-400 dark:text-zinc-500">
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
                  class="flex h-20 w-8 shrink-0 items-center justify-center rounded-none border-2 border-editorial-ink/15 bg-white text-editorial-ink transition-colors hover:border-editorial-coral/40 hover:bg-editorial-paper disabled:opacity-40 dark:bg-zinc-900 dark:text-zinc-300"
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
                      class="relative h-20 w-20 shrink-0 overflow-hidden rounded-none border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                      :class="selectedIndex === i
                        ? 'border-editorial-coral shadow-lg shadow-editorial-coral/20'
                        : 'border-editorial-ink/15 hover:border-editorial-coral/40'"
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
                  class="flex h-20 w-8 shrink-0 items-center justify-center rounded-none border-2 border-editorial-ink/15 bg-white text-editorial-ink transition-colors hover:border-editorial-coral/40 hover:bg-editorial-paper disabled:opacity-40 dark:bg-zinc-900 dark:text-zinc-300"
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
            <div class="product-details-card relative border-2 border-editorial-ink/10 bg-white p-6 shadow-product-card lg:p-10">
              <!-- Left accent bar -->
              <span class="absolute left-0 top-8 bottom-8 w-1 bg-editorial-coral/80 lg:top-10 lg:bottom-10" aria-hidden="true" />

              <div class="pl-4 lg:pl-5">
                <p
                  v-if="product.category"
                  class="inline-block rounded-none border border-editorial-coral/40 bg-editorial-coral/5 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-editorial-coral"
                >
                  {{ product.category.name }}
                </p>
                <h1 class="mt-5 font-editorial break-words text-2xl font-bold leading-tight tracking-tight text-editorial-ink sm:text-3xl lg:text-4xl">
                  {{ product.name }}
                </h1>
                <p v-if="product.short_description" class="mt-5 text-base leading-relaxed text-editorial-slate sm:text-lg">
                  {{ product.short_description }}
                </p>

                <!-- Price block -->
                <div v-if="hasPrice" class="mt-6 flex flex-wrap items-baseline gap-3 border-y border-editorial-ink/10 py-5">
                  <span class="text-xs font-semibold uppercase tracking-wider text-editorial-slate">Price</span>
                  <span class="font-editorial text-2xl font-bold text-editorial-ink sm:text-3xl">{{ formatPrice(price) }}</span>
                </div>

                <!-- Add to cart block -->
                <div class="mt-6 flex flex-wrap items-center gap-4">
                  <div class="product-qty flex items-center rounded-none border-2 border-editorial-ink/15 bg-editorial-cream/80">
                    <button
                      type="button"
                      class="flex h-12 w-12 shrink-0 items-center justify-center text-editorial-ink transition hover:bg-editorial-ink/10 hover:text-editorial-coral"
                      aria-label="Decrease quantity"
                      @click="quantity = clampQty(quantity - 1)"
                    >
                      <Minus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                    <input
                      v-model.number="quantity"
                      type="number"
                      min="1"
                      max="99"
                      class="w-16 shrink-0 border-0 bg-transparent text-center text-lg font-semibold text-editorial-ink [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                      @change="quantity = clampQty(quantity)"
                    />
                    <button
                      type="button"
                      class="flex h-12 w-12 shrink-0 items-center justify-center text-editorial-ink transition hover:bg-editorial-ink/10 hover:text-editorial-coral"
                      aria-label="Increase quantity"
                      @click="quantity = clampQty(quantity + 1)"
                    >
                      <Plus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                  </div>
                  <button
                    type="button"
                    :disabled="addingToCart"
                    class="product-add-btn inline-flex min-h-[48px] min-w-0 items-center justify-center gap-2 rounded-none border-2 border-editorial-coral bg-editorial-coral px-8 py-3.5 text-base font-bold uppercase tracking-wider text-white shadow-[4px_4px_0_0_rgba(28,25,23,0.15)] transition hover:bg-[#a83609] hover:border-[#a83609] hover:shadow-[5px_5px_0_0_rgba(28,25,23,0.2)] focus:outline-none focus:ring-2 focus:ring-editorial-coral focus:ring-offset-2 disabled:opacity-70"
                    @click="addToCart()"
                  >
                    <ShoppingCart class="h-5 w-5 shrink-0" stroke-width="2" />
                    {{ addingToCart ? 'Adding…' : 'Add to cart' }}
                  </button>
                </div>

                <!-- Delivery line -->
                <div class="mt-6 flex items-start gap-3 rounded-none border border-editorial-ink/10 bg-editorial-cream/50 p-4">
                  <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-none bg-editorial-coral/10 text-editorial-coral">
                    <Truck class="h-5 w-5" stroke-width="2" />
                  </span>
                  <p class="text-sm leading-snug text-editorial-slate">
                    {{ hasPrice ? 'Nationwide shipping across Zambia.' : 'Get pricing, MOQ & samples. We reply within 24 hours.' }}
                  </p>
                </div>

                <!-- Message to arrange viewing/collection -->
                <div class="mt-3 flex flex-wrap items-center gap-3 rounded-none border border-editorial-ink/10 bg-editorial-cream/50 p-4 sm:flex-nowrap">
                  <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-none bg-editorial-coral/10 text-editorial-coral">
                    <MessageCircle class="h-5 w-5" stroke-width="2" />
                  </span>
                  <p class="min-w-0 flex-1 text-sm leading-snug text-editorial-slate">
                    Message to arrange viewing/collection
                  </p>
                  <a
                    :href="whatsappViewingUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-none border-2 border-[#25D366] bg-[#25D366] px-4 py-2.5 text-sm font-bold uppercase tracking-wider text-white shadow-[3px_3px_0_0_rgba(0,0,0,0.1)] transition hover:bg-[#20bd5a] hover:border-[#20bd5a] focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:ring-offset-2"
                    aria-label="Message on WhatsApp to arrange viewing or collection"
                  >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                  </a>
                </div>

                <!-- Description -->
                <div
                  v-if="product.description"
                  class="product-description mt-8 border-t border-editorial-ink/10 pt-8"
                >
                  <h2 class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-editorial-slate">
                    <Package class="h-4 w-4 text-editorial-coral/70" stroke-width="2" />
                    Description
                  </h2>
                  <div
                    class="product-description-content mt-5 max-w-none rounded-xl border border-editorial-ink/8 bg-white/80 px-5 py-6 shadow-sm sm:px-7 sm:py-8"
                    v-html="product.description"
                  />
                </div>
                <p
                  v-else-if="!product.description && product.short_description"
                  class="mt-8 text-base leading-relaxed text-editorial-slate"
                >
                  {{ product.short_description }}
                </p>
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
          class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4"
          role="dialog"
          aria-modal="true"
          aria-label="Product image full size"
          @click.self="closeImageModal"
        >
          <button
            type="button"
            class="fixed right-4 top-4 z-[101] flex h-10 w-10 items-center justify-center rounded-full bg-white text-editorial-ink shadow-lg transition hover:bg-editorial-cream focus:outline-none focus:ring-2 focus:ring-editorial-coral"
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
/* Section background */
.product-detail-section {
  min-height: 60vh;
}

/* Main image: soft shadow + hover zoom */
.product-main-image {
  box-shadow:
    0 2px 8px rgba(28, 25, 23, 0.06),
    0 12px 40px rgba(28, 25, 23, 0.08);
}
.product-main-image:hover img {
  transform: scale(1.03);
}
.product-main-image img {
  transition: transform 0.5s ease-out;
}

/* Thumbnail active state */
button[aria-label]:focus-visible {
  outline: 2px solid var(--color-editorial-coral, #c2410c);
  outline-offset: 2px;
}

/* Details card */
.shadow-product-card {
  box-shadow:
    0 1px 3px rgba(28, 25, 23, 0.04),
    0 6px 20px rgba(28, 25, 23, 0.06);
}

/* Quantity input: remove spinner */
.product-qty input:focus {
  outline: none;
}

/* Add button hover */
.product-add-btn:not(:disabled):active {
  transform: translate(1px, 1px);
  box-shadow: 3px 3px 0 0 rgba(28, 25, 23, 0.2);
}

/* Full Description – modern, premium styling for editor content */
.product-description-content {
  font-size: 0.9375rem; /* 15px – readable, premium */
  letter-spacing: 0.01em;
  color: #334155; /* editorial-slate feel */
}
.product-description-content :deep(p) {
  @apply leading-[1.7] text-editorial-slate;
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
  @apply font-semibold tracking-tight text-editorial-ink;
  margin-top: 2rem;
  margin-bottom: 0.75rem;
  line-height: 1.3;
}
.product-description-content :deep(h1) { font-size: 1.375rem; }
.product-description-content :deep(h2) {
  font-size: 1.25rem;
  @apply border-l-4 border-editorial-coral pl-4;
  padding-top: 0.125rem;
  padding-bottom: 0.125rem;
}
.product-description-content :deep(h3) {
  font-size: 1.0625rem;
  @apply uppercase tracking-[0.12em] text-editorial-coral;
}
.product-description-content :deep(h4) {
  font-size: 1rem;
  @apply font-bold text-editorial-slate;
}
.product-description-content :deep(h5) { font-size: 0.9375rem; }
.product-description-content :deep(h6) { font-size: 0.875rem; @apply text-editorial-slate/90; }
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
  @apply rounded-xl border border-editorial-ink/10 bg-gradient-to-b from-editorial-paper/60 to-white/80 py-4 px-5 shadow-[0_1px_3px_rgba(28,25,23,0.06)];
}
.product-description-content :deep(ul li) {
  @apply relative py-1.5 pl-6 text-editorial-slate;
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
  background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); /* coral */
  box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.2);
}
.product-description-content :deep(ol) {
  @apply list-decimal rounded-xl border border-editorial-ink/10 bg-gradient-to-b from-editorial-paper/60 to-white/80 py-4 pl-8 pr-5 shadow-[0_1px_3px_rgba(28,25,23,0.06)] sm:pl-10;
}
.product-description-content :deep(ol li) {
  @apply py-1.5 text-editorial-slate;
  line-height: 1.6;
}
.product-description-content :deep(li + li) {
  margin-top: 0.25rem;
}

/* Inline emphasis */
.product-description-content :deep(strong),
.product-description-content :deep(b) {
  @apply font-semibold text-editorial-ink;
}
.product-description-content :deep(em),
.product-description-content :deep(i) {
  @apply italic text-editorial-slate;
}
.product-description-content :deep(a) {
  @apply font-medium text-editorial-coral underline decoration-editorial-coral/40 underline-offset-2 transition hover:decoration-editorial-coral;
}

/* Blockquote if editor outputs it */
.product-description-content :deep(blockquote) {
  @apply border-l-4 border-editorial-coral/60 bg-editorial-paper/40 py-2 pl-4 pr-3 italic text-editorial-slate;
  margin: 1.25rem 0;
  border-radius: 0 6px 6px 0;
}

/* Sticky gallery on desktop */
@media (min-width: 1024px) {
  .product-gallery-sticky {
    position: sticky;
    top: 6rem;
  }
}
</style>
