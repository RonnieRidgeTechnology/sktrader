<script setup>
import { computed, ref, watch, inject } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import { storageUrl } from '@/utils/storageUrl';
import { Package, ChevronRight, FolderTree, X, ShoppingCart } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Products' },
  categories: { type: Array, default: () => [] },
  products: { type: Array, default: () => [] },
  filterCategory: { type: String, default: null },
});

// Build tree: parents (no parent_id) with their children
const categoryTree = computed(() => {
  const list = props.categories || [];
  const parents = list.filter((c) => !c.parent_id);
  const childrenByParent = {};
  list.forEach((c) => {
    if (c.parent_id) {
      if (!childrenByParent[c.parent_id]) childrenByParent[c.parent_id] = [];
      childrenByParent[c.parent_id].push(c);
    }
  });
  return parents.map((p) => ({
    ...p,
    children: childrenByParent[p.id] || [],
  }));
});

// Expanded parent category IDs (show subcategories when expanded)
const expandedIds = ref(new Set());

function toggleExpanded(parentId) {
  const next = new Set(expandedIds.value);
  if (next.has(parentId)) next.delete(parentId);
  else next.add(parentId);
  expandedIds.value = next;
}

function isExpanded(parentId) {
  return expandedIds.value.has(parentId);
}

// Auto-expand parent that contains the currently selected category
watch(
  () => props.filterCategory,
  (slug) => {
    if (!slug) return;
    const parent = categoryTree.value.find((p) =>
      p.children.some((c) => c.slug === slug) || p.slug === slug
    );
    if (parent && parent.children.length) {
      expandedIds.value = new Set([...expandedIds.value, parent.id]);
    }
  },
  { immediate: true }
);

function setFilter(slug) {
  router.get(route('products'), slug ? { category: slug } : {}, { preserveState: true });
}

function productImageUrl(product) {
  if (product?.image_url) return product.image_url;
  return storageUrl(product?.image) ?? null;
}

function categoryDisplayName(cat) {
  return cat.parent ? `${cat.parent.name} › ${cat.name}` : cat.name;
}

// Flat list of categories for pills: All + every category (parent and child)
const categoryPills = computed(() => {
  const list = [{ slug: null, name: 'All' }];
  (props.categories || []).forEach((c) => {
    list.push({ slug: c.slug, name: c.parent ? `${c.parent.name} › ${c.name}` : c.name });
  });
  return list;
});

// Current category label for hero
const currentCategoryLabel = computed(() => {
  if (!props.filterCategory) return 'All products';
  const cat = (props.categories || []).find((c) => c.slug === props.filterCategory);
  return cat ? (cat.parent ? `${cat.parent.name} › ${cat.name}` : cat.name) : props.filterCategory;
});

// Mobile: left-sliding categories drawer
const categoriesDrawerOpen = ref(false);
function openCategoriesDrawer() {
  categoriesDrawerOpen.value = true;
}
function closeCategoriesDrawer() {
  categoriesDrawerOpen.value = false;
}
function setFilterAndClose(slug) {
  setFilter(slug);
  closeCategoriesDrawer();
}

const openCartDrawer = inject('openCartDrawer', null);
const addingProductId = ref(null);
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

const seoDefaults = computed(() => ({
  title: props.title || 'All Products | SK Traders',
  description: 'Shop curated watches, perfumes, and skin care serums. Filter by category and checkout in minutes with SK Traders.',
}));
const pageSeoProps = usePageSeo(null, seoDefaults);
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <section class="min-h-screen min-w-0 bg-luxe-obsidian">
      <!-- Luxe header strip -->
      <div class="border-b border-white/10 bg-black/40">
        <div class="luxe-container py-10">
          <nav class="flex flex-wrap items-center gap-2 text-sm text-luxe-mist" aria-label="Breadcrumb">
            <Link :href="route('home')" class="transition hover:text-luxe-gold">Home</Link>
            <ChevronRight class="h-4 w-4 shrink-0 opacity-60" stroke-width="2" />
            <Link :href="route('products')" class="font-medium text-luxe-pearl">Products</Link>
            <template v-if="filterCategory">
              <ChevronRight class="h-4 w-4 shrink-0 opacity-60" stroke-width="2" />
              <span class="text-luxe-gold">{{ currentCategoryLabel }}</span>
            </template>
          </nav>
          <p class="luxe-kicker mt-4">Collection</p>
          <h1 class="luxe-title mt-3 text-3xl sm:text-4xl lg:text-5xl">
            {{ currentCategoryLabel }}
          </h1>
          <p class="mt-4 max-w-2xl text-base text-luxe-pearl/80 sm:text-lg">
            {{ filterCategory ? 'Handpicked pieces in this category.' : 'Watches, perfumes, and skincare serums — curated for premium taste.' }}
          </p>
        </div>
      </div>

      <div class="luxe-container flex gap-0 py-10">
        <aside class="hidden w-72 shrink-0 lg:block" aria-label="Product categories">
          <div class="sticky top-24 luxe-surface rounded-3xl p-6">
            <div class="flex items-center gap-2 border-b border-white/10 pb-4">
              <FolderTree class="h-5 w-5 text-luxe-gold" stroke-width="2" />
              <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">
                Categories
              </h2>
            </div>
            <nav class="products-sidebar-nav mt-4 max-h-[calc(100vh-12rem)] space-y-1 overflow-y-auto pr-1">
              <button
                type="button"
                class="category-nav-item group flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                :class="!filterCategory
                  ? 'bg-white/10 text-luxe-pearl'
                  : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                @click="setFilter(null)"
              >
                <span class="flex items-center gap-2">
                  <Package class="h-4 w-4 shrink-0 opacity-80" stroke-width="2" />
                  All
                </span>
                <ChevronRight
                  v-if="!filterCategory"
                  class="h-4 w-4 shrink-0 text-luxe-gold"
                  stroke-width="2.5"
                />
              </button>

              <template v-for="parent in categoryTree" :key="parent.id">
                <!-- Parent category (no children) or parent with children -->
                <template v-if="parent.children.length === 0">
                  <button
                    type="button"
                    class="category-nav-item group flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                    :class="filterCategory === parent.slug
                      ? 'bg-white/10 text-luxe-pearl'
                      : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                    @click="setFilter(parent.slug)"
                  >
                    <span>{{ parent.name }}</span>
                    <ChevronRight
                      v-if="filterCategory === parent.slug"
                      class="h-4 w-4 shrink-0 text-luxe-gold"
                      stroke-width="2.5"
                    />
                  </button>
                </template>
                <template v-else>
                  <div class="mt-3 first:mt-0">
                    <button
                      type="button"
                      class="category-nav-item group flex w-full items-center gap-2 rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                      :class="filterCategory === parent.slug
                        ? 'bg-white/10 text-luxe-pearl'
                        : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                      @click="toggleExpanded(parent.id)"
                    >
                      <span
                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded transition-transform duration-200"
                        :class="isExpanded(parent.id) ? 'rotate-90' : ''"
                      >
                        <ChevronRight class="h-4 w-4 text-luxe-mist group-hover:text-luxe-gold" stroke-width="2.5" />
                      </span>
                      <span class="flex-1">{{ parent.name }}</span>
                      <ChevronRight
                        v-if="filterCategory === parent.slug"
                        class="h-4 w-4 shrink-0 text-luxe-gold"
                        stroke-width="2.5"
                      />
                    </button>
                    <div
                      v-show="isExpanded(parent.id)"
                      class="ml-3 mt-1 space-y-1 border-l border-white/10 pl-3"
                    >
                      <button
                        v-for="child in parent.children"
                        :key="child.id"
                        type="button"
                        class="category-nav-item flex w-full items-center justify-between rounded-2xl px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-[0.18em] transition"
                        :class="filterCategory === child.slug
                          ? 'bg-white/10 text-luxe-pearl'
                          : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                        @click="setFilter(child.slug)"
                      >
                        <span class="truncate">{{ child.name }}</span>
                        <ChevronRight
                          v-if="filterCategory === child.slug"
                          class="h-3.5 w-3.5 shrink-0 text-luxe-gold"
                          stroke-width="2.5"
                        />
                      </button>
                    </div>
                  </div>
                </template>
              </template>
            </nav>
          </div>
        </aside>

        <!-- Main: pills + count + grid -->
        <main class="min-w-0 flex-1 pl-0 lg:pl-10">
          <!-- Category pills (horizontal scroll) -->
          <div class="mb-5 flex min-w-0 gap-2 overflow-x-auto pb-2">
            <button
              v-for="pill in categoryPills"
              :key="pill.slug ?? 'all'"
              type="button"
              class="category-pill shrink-0 rounded-3xl border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.22em] text-luxe-mist transition hover:bg-white/10 hover:text-luxe-pearl"
              :class="(pill.slug === null && !filterCategory) || (pill.slug && pill.slug === filterCategory)
                ? 'border-luxe-gold/40 bg-luxe-gold text-black hover:bg-luxe-gold hover:text-black'
                : ''"
              @click="setFilter(pill.slug)"
            >
              {{ pill.name }}
            </button>
          </div>

          <!-- Product count + decorative line -->
          <div class="mb-8 flex min-w-0 flex-wrap items-center gap-3">
            <span class="text-sm text-luxe-mist">
              Showing <strong class="font-semibold text-luxe-pearl">{{ products.length }}</strong>
              {{ products.length === 1 ? 'piece' : 'pieces' }}
            </span>
            <span class="h-px flex-1 min-w-[60px] max-w-[160px] bg-gradient-to-r from-luxe-gold/50 to-transparent" aria-hidden="true" />
          </div>

          <div class="mb-5 flex min-w-0 lg:hidden">
            <button
              type="button"
              class="luxe-btn luxe-btn-ghost w-full justify-center"
              aria-label="Open categories"
              @click="openCategoriesDrawer()"
            >
              <FolderTree class="h-5 w-5 text-luxe-gold" stroke-width="2" />
              Categories
            </button>
          </div>

          <!-- Mobile categories drawer (slide from left) -->
          <Teleport to="body">
            <Transition name="drawer">
              <div
                v-show="categoriesDrawerOpen"
                class="fixed inset-0 z-[100] lg:hidden"
                aria-modal="true"
                aria-label="Product categories"
                role="dialog"
              >
                <!-- Backdrop -->
                <div
                  class="categories-drawer-backdrop absolute inset-0 bg-black/70 backdrop-blur-sm"
                  aria-hidden="true"
                  @click="closeCategoriesDrawer()"
                />
                <!-- Panel (slides from left) -->
                <div
                  class="categories-drawer-panel absolute left-0 top-0 bottom-0 w-[min(360px,88vw)] overflow-hidden border-r border-white/10 bg-black/60 backdrop-blur-2xl shadow-2xl"
                >
                  <div class="flex h-full flex-col">
                    <div class="flex shrink-0 items-center justify-between border-b border-white/10 px-5 py-4">
                      <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-luxe-mist">
                        <FolderTree class="h-5 w-5 text-luxe-gold" stroke-width="2" />
                        Categories
                      </span>
                      <button
                        type="button"
                        class="luxe-btn-icon"
                        aria-label="Close categories"
                        @click="closeCategoriesDrawer()"
                      >
                        <X class="h-5 w-5" stroke-width="2" />
                      </button>
                    </div>
                    <nav class="products-sidebar-nav min-h-0 flex-1 overflow-y-auto p-5">
                      <button
                        type="button"
                        class="category-nav-item group flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                        :class="!filterCategory
                          ? 'bg-white/10 text-luxe-pearl'
                          : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                        @click="setFilterAndClose(null)"
                      >
                        <span class="flex items-center gap-2">
                          <Package class="h-4 w-4 shrink-0 opacity-80" stroke-width="2" />
                          All
                        </span>
                        <ChevronRight
                          v-if="!filterCategory"
                          class="h-4 w-4 shrink-0 text-luxe-gold"
                          stroke-width="2.5"
                        />
                      </button>
                      <template v-for="parent in categoryTree" :key="parent.id">
                        <template v-if="parent.children.length === 0">
                            <button
                              type="button"
                              class="category-nav-item group flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                              :class="filterCategory === parent.slug
                                ? 'bg-white/10 text-luxe-pearl'
                                : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                              @click="setFilterAndClose(parent.slug)"
                            >
                              <span>{{ parent.name }}</span>
                              <ChevronRight
                                v-if="filterCategory === parent.slug"
                                class="h-4 w-4 shrink-0 text-luxe-gold"
                                stroke-width="2.5"
                              />
                            </button>
                        </template>
                        <template v-else>
                          <div class="mt-3 first:mt-0">
                            <button
                              type="button"
                              class="category-nav-item group flex w-full items-center gap-2 rounded-2xl px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] transition"
                              :class="filterCategory === parent.slug
                                ? 'bg-white/10 text-luxe-pearl'
                                : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                              @click="toggleExpanded(parent.id)"
                            >
                              <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded transition-transform duration-200"
                                :class="isExpanded(parent.id) ? 'rotate-90' : ''"
                              >
                                <ChevronRight class="h-4 w-4 text-luxe-mist group-hover:text-luxe-gold" stroke-width="2.5" />
                              </span>
                              <span class="flex-1">{{ parent.name }}</span>
                              <ChevronRight
                                v-if="filterCategory === parent.slug"
                                class="h-4 w-4 shrink-0 text-luxe-gold"
                                stroke-width="2.5"
                              />
                            </button>
                            <div
                              v-show="isExpanded(parent.id)"
                              class="ml-3 mt-1 space-y-1 border-l border-white/10 pl-3"
                            >
                              <button
                                v-for="child in parent.children"
                                :key="child.id"
                                type="button"
                                class="category-nav-item flex w-full items-center justify-between rounded-2xl px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-[0.18em] transition"
                                :class="filterCategory === child.slug
                                  ? 'bg-white/10 text-luxe-pearl'
                                  : 'text-luxe-mist hover:bg-white/5 hover:text-luxe-pearl'"
                                @click="setFilterAndClose(child.slug)"
                              >
                                <span class="truncate">{{ child.name }}</span>
                                <ChevronRight
                                  v-if="filterCategory === child.slug"
                                  class="h-3.5 w-3.5 shrink-0 text-luxe-gold"
                                  stroke-width="2.5"
                                />
                              </button>
                            </div>
                          </div>
                        </template>
                      </template>
                    </nav>
                  </div>
                </div>
              </div>
            </Transition>
          </Teleport>

          <div class="product-grid grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 sm:gap-6">
            <div
              v-for="(product, index) in products"
              :key="product.id"
              class="product-card product-card-reveal group relative flex min-w-0 flex-col overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl transition-all duration-300 hover:bg-white/10"
              :style="{ animationDelay: `${Math.min(index * 60, 420)}ms` }"
            >
              <div class="product-card-border absolute inset-0 transition-all duration-300" aria-hidden="true" />
              <Link
                :href="route('products.show', product.slug)"
                class="flex min-w-0 flex-1 flex-col"
              >
                <div class="relative aspect-[4/3] min-h-0 w-full overflow-hidden bg-black/30">
                  <img
                    v-if="productImageUrl(product)"
                    :src="productImageUrl(product)"
                    :alt="product.name"
                    loading="lazy"
                    class="product-card-img absolute inset-0 h-full w-full object-cover object-center transition-all duration-700 ease-out"
                    @error="($event.target).style.display = 'none'"
                  />
                  <div
                    v-show="!productImageUrl(product)"
                    class="flex h-full w-full items-center justify-center text-luxe-mist"
                  >
                    <Package class="h-14 w-14 opacity-40" stroke-width="1.5" />
                  </div>
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/15 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true" />
                  <div class="product-card-view-overlay absolute inset-0 flex items-center justify-center opacity-0 transition-all duration-300 group-hover:opacity-100">
                    <span class="inline-flex items-center gap-2 rounded-3xl border border-white/15 bg-white/10 px-5 py-3 text-xs font-semibold uppercase tracking-[0.25em] text-luxe-pearl shadow-luxe backdrop-blur-xl">
                      View details
                      <ChevronRight class="h-4 w-4" stroke-width="2.5" />
                    </span>
                  </div>
                </div>
                <div class="relative flex flex-1 flex-col px-5 pt-5 pb-2">
                  <span
                    v-if="product.category"
                    class="text-[11px] font-semibold uppercase tracking-[0.25em] text-luxe-gold/90"
                  >
                    {{ product.category.parent ? `${product.category.parent.name} › ${product.category.name}` : product.category.name }}
                  </span>
                  <h2 class="mt-3 font-display text-lg font-semibold leading-snug tracking-tight text-luxe-pearl">
                    {{ product.name }}
                  </h2>
                  <p class="mt-3 flex-1 text-sm leading-relaxed text-luxe-mist/90 line-clamp-2">
                    {{ product.short_description }}
                  </p>
                  <p v-if="product.price != null && Number(product.price) > 0" class="mt-4 font-display text-lg font-semibold text-luxe-pearl">
                    {{ new Intl.NumberFormat('en-ZM', { style: 'currency', currency: 'ZMW', minimumFractionDigits: 2 }).format(Number(product.price)) }}
                  </p>
                  <span class="product-card-cta mt-5 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.22em] text-luxe-gold/90">
                    View
                    <ChevronRight class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" stroke-width="2.5" />
                  </span>
                </div>
              </Link>
              <div class="border-t border-white/10 px-5 pb-5 pt-4">
                <button
                  type="button"
                  :disabled="addingProductId === product.id"
                  class="luxe-btn luxe-btn-ghost w-full justify-center"
                  @click="addToCart(product.id, $event)"
                >
                  <ShoppingCart class="h-4 w-4 shrink-0" stroke-width="2" />
                  {{ addingProductId === product.id ? 'Adding…' : 'Add to cart' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Empty state -->
          <div
            v-if="products.length === 0"
            class="product-empty-state mt-16 flex flex-col items-center justify-center rounded-3xl border border-dashed border-white/15 bg-white/5 px-6 py-14 text-center sm:px-12 sm:py-20"
          >
            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/5 text-luxe-gold">
              <Package class="h-8 w-8" stroke-width="1.5" />
            </div>
            <h2 class="font-display mt-6 text-2xl font-semibold text-luxe-pearl">
              No products found
            </h2>
            <p class="mt-3 max-w-sm text-sm text-luxe-mist/90">
              Try another category or browse everything we have.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
              <Link
                :href="route('products')"
                class="luxe-btn luxe-btn-primary"
              >
                View all products
                <ChevronRight class="h-4 w-4" stroke-width="2.5" />
              </Link>
              <Link
                :href="route('contact')"
                class="luxe-btn luxe-btn-ghost"
              >
                Contact us
              </Link>
            </div>
          </div>
        </main>
      </div>
    </section>
  </AppLayout>
</template>

<style scoped>
/* Mobile categories drawer: backdrop fades, panel slides from left */
.drawer-enter-active .categories-drawer-backdrop,
.drawer-leave-active .categories-drawer-backdrop {
  transition: opacity 0.25s ease;
}
.drawer-enter-active .categories-drawer-panel,
.drawer-leave-active .categories-drawer-panel {
  transition: transform 0.25s ease;
}
.drawer-enter-from .categories-drawer-backdrop,
.drawer-leave-to .categories-drawer-backdrop {
  opacity: 0;
}
.drawer-enter-from .categories-drawer-panel,
.drawer-leave-to .categories-drawer-panel {
  transform: translateX(-100%);
}

/* Staggered reveal for product cards */
.product-card-reveal {
  animation: product-reveal 0.6s ease-out both;
}
@keyframes product-reveal {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Product cards: premium border, shadow, hover */
.product-card {
  box-shadow:
    0 10px 40px -16px rgba(0, 0, 0, 0.55),
    inset 0 2px 0 rgba(255, 255, 255, 0.04);
}
.product-card:hover {
  transform: translateY(-4px);
  box-shadow:
    0 24px 80px -36px rgba(0, 0, 0, 0.75),
    inset 0 2px 0 rgba(255, 255, 255, 0.05);
}
.product-card-border {
  border: 1px solid rgba(255, 255, 255, 0.08);
  pointer-events: none;
}
.product-card:hover .product-card-border {
  border-color: rgba(199, 164, 93, 0.28);
  box-shadow: 0 0 0 1px rgba(199, 164, 93, 0.10);
}
.product-card:hover .product-card-img {
  transform: scale(1.08);
}
.product-card-cta {
  letter-spacing: 0.02em;
}
.product-card:hover .product-card-cta {
  color: #c7a45d;
}

.products-sidebar-nav {
  scrollbar-gutter: stable;
  scrollbar-width: thin;
  scrollbar-color: rgb(212 212 212) transparent;
}
.dark .products-sidebar-nav {
  scrollbar-color: rgb(63 63 70) transparent;
}
.products-sidebar-nav::-webkit-scrollbar {
  width: 8px;
}
.products-sidebar-nav::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 4px;
}
.products-sidebar-nav::-webkit-scrollbar-thumb {
  background: rgb(212 212 212);
  border-radius: 4px;
}
.products-sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: rgb(163 163 163);
}
.dark .products-sidebar-nav::-webkit-scrollbar-thumb {
  background: rgb(63 63 70);
}
.dark .products-sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: rgb(82 82 91);
}
</style>
