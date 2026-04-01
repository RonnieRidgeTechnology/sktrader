<script setup>
import { computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ShoppingBag, Minus, Plus, Trash2 } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Shopping Cart' },
  cart: {
    type: Object,
    default: () => ({ items: [], count: 0, subtotal: 0 }),
  },
});

const items = computed(() => props.cart?.items || []);
const subtotal = computed(() => props.cart?.subtotal ?? 0);
const isEmpty = computed(() => items.value.length === 0);

function formatPrice(value) {
  return new Intl.NumberFormat('en-ZM', {
    style: 'currency',
    currency: 'ZMW',
    minimumFractionDigits: 2,
  }).format(value);
}

function updateQuantity(productId, newQty) {
  if (newQty < 1) return;
  router.patch(route('cart.update'), { product_id: productId, quantity: newQty }, { preserveScroll: true });
}

function removeItem(productId) {
  router.delete(route('cart.remove', productId), { preserveScroll: true });
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-editorial-ink/10 bg-editorial-cream py-12 sm:py-16 md:py-24">
      <div class="mx-auto min-w-0 max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <h1 class="font-editorial text-3xl font-bold tracking-tight text-editorial-ink sm:text-4xl">
            {{ title }}
          </h1>
          <Link
            :href="route('products')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-editorial-coral transition hover:text-editorial-coral/80"
          >
            Continue shopping
          </Link>
        </div>

        <div v-if="isEmpty" class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-12 text-center shadow-premium">
          <ShoppingBag class="mx-auto h-16 w-16 text-editorial-slate/40" stroke-width="1.5" />
          <p class="mt-4 font-editorial text-xl font-semibold text-editorial-ink">Your cart is empty</p>
          <p class="mt-2 text-editorial-slate">Add items from our collection to get started.</p>
          <Link
            :href="route('products')"
            class="mt-8 inline-flex min-h-[48px] items-center justify-center border-2 border-editorial-ink bg-editorial-ink px-8 py-3.5 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90"
          >
            Shop products
          </Link>
        </div>

        <div v-else class="space-y-8">
          <div class="overflow-hidden rounded-2xl border-2 border-editorial-ink/10 bg-white shadow-premium">
            <ul class="divide-y divide-editorial-ink/10">
              <li
                v-for="item in items"
                :key="item.product_id"
                class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:gap-6"
              >
                <Link
                  :href="route('products.show', item.slug)"
                  class="relative h-28 w-full shrink-0 overflow-hidden rounded-xl border border-editorial-ink/10 bg-editorial-paper sm:h-24 sm:w-24"
                >
                  <img
                    v-if="item.image_url"
                    :src="item.image_url"
                    :alt="item.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="flex h-full w-full items-center justify-center text-editorial-slate/40">
                    <ShoppingBag class="h-10 w-10" stroke-width="1.5" />
                  </div>
                </Link>
                <div class="min-w-0 flex-1">
                  <Link
                    :href="route('products.show', item.slug)"
                    class="font-editorial text-lg font-semibold text-editorial-ink hover:text-editorial-coral"
                  >
                    {{ item.name }}
                  </Link>
                  <p class="mt-1 text-sm text-editorial-slate">
                    {{ formatPrice(item.price) }} each
                  </p>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                  <div class="flex items-center border-2 border-editorial-ink/15 bg-editorial-cream">
                    <button
                      type="button"
                      class="flex h-10 w-10 items-center justify-center text-editorial-ink transition hover:bg-editorial-ink/10"
                      :aria-label="`Decrease quantity of ${item.name}`"
                      @click="updateQuantity(item.product_id, item.quantity - 1)"
                    >
                      <Minus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                    <span class="min-w-[2.5rem] text-center text-sm font-semibold text-editorial-ink">
                      {{ item.quantity }}
                    </span>
                    <button
                      type="button"
                      class="flex h-10 w-10 items-center justify-center text-editorial-ink transition hover:bg-editorial-ink/10"
                      :aria-label="`Increase quantity of ${item.name}`"
                      @click="updateQuantity(item.product_id, item.quantity + 1)"
                    >
                      <Plus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                  </div>
                  <p class="w-24 text-right font-editorial text-lg font-semibold text-editorial-ink">
                    {{ formatPrice(item.price * item.quantity) }}
                  </p>
                  <button
                    type="button"
                    class="rounded-lg p-2 text-editorial-slate transition hover:bg-red-50 hover:text-red-600"
                    :aria-label="`Remove ${item.name} from cart`"
                    @click="removeItem(item.product_id)"
                  >
                    <Trash2 class="h-5 w-5" stroke-width="2" />
                  </button>
                </div>
              </li>
            </ul>
          </div>

          <div class="flex flex-col items-end gap-6 rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 shadow-premium sm:p-8">
            <div class="flex w-full items-center justify-between text-lg sm:w-auto sm:min-w-[280px]">
              <span class="font-medium text-editorial-slate">Subtotal</span>
              <span class="font-editorial text-2xl font-bold text-editorial-ink">{{ formatPrice(subtotal) }}</span>
            </div>
            <p class="text-sm text-editorial-slate">Shipping & delivery calculated at checkout. Pay when you receive (COD).</p>
            <Link
              :href="route('checkout')"
              class="w-full min-h-[52px] sm:w-auto inline-flex items-center justify-center border-2 border-editorial-ink bg-editorial-ink px-8 py-3.5 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90"
            >
              Proceed to checkout
            </Link>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
