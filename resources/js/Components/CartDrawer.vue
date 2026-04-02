<script setup>
import { computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useStoreCurrency } from '@/composables/useStoreCurrency';
import { X, ShoppingBag, Minus, Plus, Trash2 } from 'lucide-vue-next';

const props = defineProps({
  open: { type: Boolean, default: false },
});

const emit = defineEmits(['close']);

const page = usePage();
const cart = computed(() => page.props.cartDrawer || { items: [], subtotal: 0 });
const items = computed(() => cart.value.items || []);
const subtotal = computed(() => cart.value.subtotal ?? 0);
const isEmpty = computed(() => items.value.length === 0);
const { formatMoney } = useStoreCurrency();

function formatPrice(value) {
  return formatMoney(value);
}

function close() {
  emit('close');
}

function updateQuantity(productId, newQty) {
  if (newQty < 1) return;
  router.patch(route('cart.update'), { product_id: productId, quantity: newQty }, { preserveScroll: true });
}

function removeItem(productId) {
  router.delete(route('cart.remove', productId), { preserveScroll: true });
}

// Prevent body scroll when drawer is open
watch(
  () => props.open,
  (isOpen) => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
      document.documentElement.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
      document.documentElement.style.overflow = '';
    }
  },
  { immediate: true }
);
</script>

<template>
  <Teleport to="body">
    <Transition name="drawer-overlay">
      <div
        v-show="open"
        class="fixed inset-0 z-[100]"
        aria-hidden="!open"
      >
        <!-- Backdrop -->
        <div
          class="absolute inset-0 bg-editorial-ink/40 backdrop-blur-sm transition-opacity duration-300"
          aria-label="Close cart"
          @click="close"
        />
        <!-- Drawer panel -->
        <Transition name="drawer-panel">
          <aside
            v-show="open"
            class="absolute right-0 top-0 flex h-[100dvh] max-h-[100dvh] w-full max-w-[min(100vw,24rem)] flex-col bg-white pt-[env(safe-area-inset-top,0px)] shadow-2xl dark:bg-zinc-900 sm:max-w-[340px]"
            role="dialog"
            aria-modal="true"
            aria-label="Shopping cart"
          >
            <!-- Header -->
            <div class="flex shrink-0 items-center justify-between border-b border-editorial-ink/10 px-4 py-4 sm:px-6 sm:py-5">
              <h2 class="font-editorial text-xl font-semibold tracking-tight text-editorial-ink dark:text-white">
                Your cart
              </h2>
              <button
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-full text-editorial-slate transition hover:bg-editorial-ink/10 hover:text-editorial-ink dark:hover:bg-zinc-700 dark:hover:text-white"
                aria-label="Close cart"
                @click="close"
              >
                <X class="h-5 w-5" stroke-width="2" />
              </button>
            </div>

            <!-- Content -->
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden">
              <div v-if="isEmpty" class="flex flex-1 flex-col items-center justify-center px-6 py-16 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-editorial-paper dark:bg-zinc-800">
                  <ShoppingBag class="h-10 w-10 text-editorial-slate/50" stroke-width="1.5" />
                </div>
                <p class="mt-5 font-editorial text-lg font-semibold text-editorial-ink dark:text-white">Your cart is empty</p>
                <p class="mt-1 text-sm text-editorial-slate dark:text-zinc-400">Add items from our collection.</p>
                <Link
                  :href="route('products')"
                  class="mt-6 inline-flex items-center justify-center rounded-none border-2 border-editorial-ink bg-editorial-ink px-6 py-3 font-semibold text-white transition hover:bg-editorial-ink/90"
                  @click="close"
                >
                  Continue shopping
                </Link>
              </div>

              <template v-else>
                <ul class="min-h-0 flex-1 overflow-y-auto px-4 py-4">
                  <li
                    v-for="item in items"
                    :key="item.product_id"
                    class="group flex gap-4 border-b border-editorial-ink/5 py-4 last:border-0"
                  >
                    <Link
                      :href="route('products.show', item.slug)"
                      class="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-editorial-paper dark:bg-zinc-800"
                      @click="close"
                    >
                      <img
                        v-if="item.image_url"
                        :src="item.image_url"
                        :alt="item.name"
                        class="h-full w-full object-cover"
                      />
                      <div v-else class="flex h-full w-full items-center justify-center text-editorial-slate/40">
                        <ShoppingBag class="h-8 w-8" stroke-width="1.5" />
                      </div>
                    </Link>
                    <div class="min-w-0 flex-1">
                      <Link
                        :href="route('products.show', item.slug)"
                        class="font-medium text-editorial-ink line-clamp-2 hover:text-editorial-coral dark:text-white dark:hover:text-editorial-coral"
                        @click="close"
                      >
                        {{ item.name }}
                      </Link>
                      <p class="mt-0.5 text-sm text-editorial-slate dark:text-zinc-400">
                        {{ formatPrice(item.price) }} × {{ item.quantity }}
                      </p>
                      <div class="mt-2 flex items-center gap-2">
                        <div class="flex items-center rounded-lg border border-editorial-ink/15 bg-white dark:border-zinc-600 dark:bg-zinc-800">
                          <button
                            type="button"
                            class="flex h-8 w-8 items-center justify-center text-editorial-ink hover:bg-editorial-ink/5 dark:text-zinc-300 dark:hover:bg-zinc-700"
                            aria-label="Decrease quantity"
                            @click="updateQuantity(item.product_id, item.quantity - 1)"
                          >
                            <Minus class="h-3.5 w-3.5" stroke-width="2.5" />
                          </button>
                          <span class="min-w-[2rem] text-center text-sm font-medium">{{ item.quantity }}</span>
                          <button
                            type="button"
                            class="flex h-8 w-8 items-center justify-center text-editorial-ink hover:bg-editorial-ink/5 dark:text-zinc-300 dark:hover:bg-zinc-700"
                            aria-label="Increase quantity"
                            @click="updateQuantity(item.product_id, item.quantity + 1)"
                          >
                            <Plus class="h-3.5 w-3.5" stroke-width="2.5" />
                          </button>
                        </div>
                        <button
                          type="button"
                          class="rounded p-1.5 text-editorial-slate opacity-70 transition hover:bg-red-50 hover:text-red-600 hover:opacity-100 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                          aria-label="Remove from cart"
                          @click="removeItem(item.product_id)"
                        >
                          <Trash2 class="h-4 w-4" stroke-width="2" />
                        </button>
                      </div>
                    </div>
                    <p class="shrink-0 text-right font-semibold text-editorial-ink dark:text-white">
                      {{ formatPrice(item.price * item.quantity) }}
                    </p>
                  </li>
                </ul>

                <!-- Footer -->
                <div class="shrink-0 border-t border-editorial-ink/10 bg-editorial-cream/50 px-4 py-4 pb-[max(1.25rem,env(safe-area-inset-bottom,0px))] dark:bg-zinc-800/50 sm:px-6 sm:py-5 sm:pb-5">
                  <div class="flex items-center justify-between text-lg">
                    <span class="font-medium text-editorial-slate dark:text-zinc-400">Subtotal</span>
                    <span class="font-editorial text-xl font-bold text-editorial-ink dark:text-white">{{ formatPrice(subtotal) }}</span>
                  </div>
                  <p class="mt-1 text-xs text-editorial-slate dark:text-zinc-500">COD. Shipping at checkout.</p>
                  <div class="mt-4 flex flex-col gap-3">
                    <Link
                      :href="route('checkout')"
                      class="flex items-center justify-center rounded-none border-2 border-editorial-ink bg-editorial-ink py-3.5 font-semibold text-white shadow-lg transition hover:bg-editorial-ink/90"
                      @click="close"
                    >
                      Checkout
                    </Link>
                    <Link
                      :href="route('cart')"
                      class="flex items-center justify-center rounded-none border-2 border-editorial-ink/20 py-3 font-medium text-editorial-ink transition hover:bg-editorial-ink/5 dark:border-zinc-600 dark:text-white dark:hover:bg-zinc-700"
                      @click="close"
                    >
                      View full cart
                    </Link>
                  </div>
                </div>
              </template>
            </div>
          </aside>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.drawer-overlay-enter-active,
.drawer-overlay-leave-active {
  transition: opacity 0.3s ease;
}
.drawer-overlay-enter-from,
.drawer-overlay-leave-to {
  opacity: 0;
}
.drawer-panel-enter-active,
.drawer-panel-leave-active {
  transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}
.drawer-panel-enter-from,
.drawer-panel-leave-to {
  transform: translateX(100%);
}
</style>
