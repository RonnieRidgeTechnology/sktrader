<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Package } from 'lucide-vue-next';
import { storageUrl } from '@/utils/storageUrl';

const props = defineProps({
  product: { type: Object, default: null },
  images: { type: Array, default: () => [] },
  section: { type: Object, default: null },
});

const spotlightEyebrow = computed(() => props.section?.eyebrow || 'In the spotlight');

const page = usePage();
function toMediaUrl(path) {
  if (!path || typeof path !== 'string') return null;
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return storageUrl(path);
}
const imageList = computed(() => {
  const arr = Array.isArray(props.images) ? props.images.map(toMediaUrl).filter(Boolean) : [];
  const fallback = props.product?.image_url || (props.product?.image ? toMediaUrl(props.product.image) : null);
  if (arr.length) return arr.slice(0, 3);
  return fallback ? [fallback] : [];
});

const whatsappUrl = computed(() => {
  const num = (page.props.zuaaz?.whatsapp?.primary || '+260970000000').replace(/\D/g, '');
  const text = props.product ? encodeURIComponent(`Hi, I'm interested in: ${props.product.name}`) : '';
  return text ? `https://wa.me/${num}?text=${text}` : `https://wa.me/${num}`;
});
</script>

<template>
  <section class="spotlight relative overflow-visible bg-[#f5f2ed] py-16 sm:py-24 md:py-32" aria-label="Spotlight">
    <div class="mx-auto grid min-w-0 max-w-7xl grid-cols-1 gap-0 px-4 lg:grid-cols-12 lg:gap-14 lg:px-8">
      <!-- Left: main image with 2 small overlapping images (top-left and bottom-right, half out) -->
      <div class="relative min-h-[50vh] overflow-visible lg:col-span-7 lg:min-h-[420px]">
        <template v-if="imageList.length">
          <!-- Main image -->
          <Link
            v-if="product"
            :href="route('products.show', product.slug)"
            class="spotlight-img-main group absolute inset-0 block overflow-visible lg:left-0 lg:top-0 lg:bottom-0 lg:right-0"
          >
            <div class="absolute inset-0 overflow-hidden">
              <img
                :src="imageList[0]"
                :alt="product.name"
                class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]"
                loading="lazy"
              />
              <div class="absolute inset-0 border-4 border-white shadow-xl transition duration-300 group-hover:shadow-2xl" aria-hidden="true" />
              <div class="absolute inset-0 bg-[#0f0e0d]/0 transition duration-300 group-hover:bg-[#0f0e0d]/8" />
            </div>
          </Link>
          <div
            v-else
            class="spotlight-img-main absolute inset-0 overflow-hidden lg:left-0 lg:top-0 lg:bottom-0 lg:right-0"
          >
            <img
              :src="imageList[0]"
              alt="Collection"
              class="h-full w-full object-cover"
              loading="lazy"
            />
            <div class="absolute inset-0 border-4 border-white shadow-xl" aria-hidden="true" />
          </div>

          <!-- Small image: top-left, half out of main image -->
          <div
            class="spotlight-corner-img absolute left-0 top-0 z-10 w-[38%] overflow-hidden border-2 border-white shadow-lg lg:w-[32%]"
            style="aspect-ratio: 1; transform: translate(-40%, -40%);"
          >
            <img
              :src="imageList[1] ?? imageList[0]"
              alt=""
              class="h-full w-full object-cover"
              loading="lazy"
            />
          </div>
          <!-- Small image: bottom-right, half out of main image -->
          <div
            class="spotlight-corner-img absolute bottom-0 right-0 z-10 w-[38%] overflow-hidden border-2 border-white shadow-lg lg:w-[32%]"
            style="aspect-ratio: 1; transform: translate(40%, 40%);"
          >
            <img
              :src="imageList[2] ?? imageList[1] ?? imageList[0]"
              alt=""
              class="h-full w-full object-cover"
              loading="lazy"
            />
          </div>
        </template>

        <!-- No images: placeholder -->
        <div
          v-else
          class="absolute inset-0 flex items-center justify-center bg-[#ebe6de]"
        >
          <Link
            v-if="product"
            :href="route('products.show', product.slug)"
            class="font-editorial text-xl font-semibold text-[#44403c] underline-offset-4 hover:underline"
          >
            View {{ product.name }}
          </Link>
          <Link
            v-else-if="product === null"
            :href="route('products')"
            class="font-editorial text-xl font-semibold text-[#44403c] underline-offset-4 hover:underline"
          >
            Explore the collection
          </Link>
          <Package v-else class="h-24 w-24 text-[#44403c]/40" stroke-width="1" />
        </div>
      </div>

      <!-- Right: content (no background for consistency) -->
      <div class="flex flex-col justify-center py-12 lg:col-span-5 lg:py-24">
        <div class="spotlight-content relative px-6 py-8 sm:px-8 sm:py-10">
          <span
            class="absolute left-0 top-8 bottom-8 w-1 rounded-full bg-[#c2410c] sm:top-10 sm:bottom-10"
            aria-hidden="true"
          />
          <div class="pl-4 sm:pl-5">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#c2410c]">
              {{ spotlightEyebrow }}
            </p>
            <template v-if="product">
              <h2 class="font-editorial mt-4 text-2xl font-semibold leading-tight tracking-tight text-[#1c1917] sm:text-3xl md:text-4xl">
                {{ product.name }}
              </h2>
              <p
                v-if="product.short_description"
                class="mt-5 max-w-md text-base leading-relaxed text-[#44403c]"
              >
                {{ product.short_description }}
              </p>
              <div class="mt-8 flex flex-wrap gap-3">
                <Link
                  :href="route('products.show', product.slug)"
                  class="inline-flex min-h-[48px] items-center justify-center rounded-none border-2 border-[#1c1917] bg-[#1c1917] px-5 py-3 text-sm font-semibold text-[#f5f2ed] shadow-[4px_4px_0_0_#1c1917/20] transition hover:bg-[#2d2a28] hover:shadow-[5px_5px_0_0_#1c1917/25]"
                >
                  View details
                </Link>
                <a
                  :href="whatsappUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex min-h-[48px] items-center justify-center rounded-none border-2 border-[#1c1917] bg-transparent px-5 py-3 text-sm font-semibold text-[#1c1917] transition hover:bg-[#1c1917]/5"
                >
                  Ask on WhatsApp
                </a>
              </div>
            </template>
            <template v-else>
              <h2 class="font-editorial mt-4 text-2xl font-semibold leading-tight tracking-tight text-[#1c1917] sm:text-3xl md:text-4xl">
                Handpicked for your space
              </h2>
              <p class="mt-5 max-w-md text-base leading-relaxed text-[#44403c]">
                Curated watches, perfumes, and skin care serums. Order online or chat with our WhatsApp concierge for quick guidance.
              </p>
              <Link
                :href="route('products')"
                class="mt-8 inline-flex min-h-[48px] w-fit items-center justify-center rounded-none border-2 border-[#1c1917] bg-[#1c1917] px-5 py-3 text-sm font-semibold text-[#f5f2ed] shadow-[4px_4px_0_0_#1c1917/20] transition hover:bg-[#2d2a28]"
              >
                Explore collection
              </Link>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.spotlight-img-main {
  border-radius: 0;
}
.spotlight-corner-img {
  border-radius: 0;
}
</style>
