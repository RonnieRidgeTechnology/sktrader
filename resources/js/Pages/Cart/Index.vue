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
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-12 sm:py-16 md:py-24">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-5xl">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <h1 class="font-display text-3xl font-semibold tracking-tight text-luxe-pearl sm:text-4xl">
            {{ title }}
          </h1>
          <Link
            :href="route('products')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-luxe-gold transition hover:opacity-90"
          >
            Continue shopping
          </Link>
        </div>

        <div v-if="isEmpty" class="luxe-surface-strong rounded-3xl p-12 text-center">
          <ShoppingBag class="mx-auto h-16 w-16 text-luxe-mist/60" stroke-width="1.5" />
          <p class="mt-5 font-display text-2xl font-semibold text-luxe-pearl">Your cart is empty</p>
          <p class="mt-3 text-luxe-pearl/80">Add items from our collection to get started.</p>
          <Link
            :href="route('products')"
            class="mt-8 luxe-btn luxe-btn-primary"
          >
            Shop products
          </Link>
        </div>

        <div v-else class="space-y-8">
          <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-luxe backdrop-blur-xl">
            <ul class="divide-y divide-white/10">
              <li
                v-for="item in items"
                :key="item.product_id"
                class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:gap-6"
              >
                <Link
                  :href="route('products.show', item.slug)"
                  class="relative h-28 w-full shrink-0 overflow-hidden rounded-3xl border border-white/10 bg-black/30 sm:h-24 sm:w-24"
                >
                  <img
                    v-if="item.image_url"
                    :src="item.image_url"
                    :alt="item.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="flex h-full w-full items-center justify-center text-luxe-mist/60">
                    <ShoppingBag class="h-10 w-10" stroke-width="1.5" />
                  </div>
                </Link>
                <div class="min-w-0 flex-1">
                  <Link
                    :href="route('products.show', item.slug)"
                    class="font-display text-lg font-semibold text-luxe-pearl hover:text-luxe-gold"
                  >
                    {{ item.name }}
                  </Link>
                  <p class="mt-1 text-sm text-luxe-mist">
                    {{ formatPrice(item.price) }} each
                  </p>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                  <div class="flex items-center rounded-3xl border border-white/10 bg-black/30">
                    <button
                      type="button"
                      class="flex h-10 w-10 items-center justify-center text-luxe-pearl transition hover:bg-white/5"
                      :aria-label="`Decrease quantity of ${item.name}`"
                      @click="updateQuantity(item.product_id, item.quantity - 1)"
                    >
                      <Minus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                    <span class="min-w-[2.5rem] text-center text-sm font-semibold text-luxe-pearl">
                      {{ item.quantity }}
                    </span>
                    <button
                      type="button"
                      class="flex h-10 w-10 items-center justify-center text-luxe-pearl transition hover:bg-white/5"
                      :aria-label="`Increase quantity of ${item.name}`"
                      @click="updateQuantity(item.product_id, item.quantity + 1)"
                    >
                      <Plus class="h-4 w-4" stroke-width="2.5" />
                    </button>
                  </div>
                  <p class="w-24 text-right font-display text-lg font-semibold text-luxe-pearl">
                    {{ formatPrice(item.price * item.quantity) }}
                  </p>
                  <button
                    type="button"
                    class="rounded-2xl p-2 text-luxe-mist transition hover:bg-white/5 hover:text-red-300"
                    :aria-label="`Remove ${item.name} from cart`"
                    @click="removeItem(item.product_id)"
                  >
                    <Trash2 class="h-5 w-5" stroke-width="2" />
                  </button>
                </div>
              </li>
            </ul>
          </div>

          <div class="flex flex-col items-end gap-6 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-luxe backdrop-blur-xl sm:p-8">
            <div class="flex w-full items-center justify-between text-lg sm:w-auto sm:min-w-[280px]">
              <span class="font-medium text-luxe-mist">Subtotal</span>
              <span class="font-display text-2xl font-semibold text-luxe-pearl">{{ formatPrice(subtotal) }}</span>
            </div>
            <p class="text-sm text-luxe-pearl/80">Shipping & delivery calculated at checkout. Secure payment options available.</p>
            <Link
              :href="route('checkout')"
              class="w-full sm:w-auto luxe-btn luxe-btn-primary"
            >
              Proceed to checkout
            </Link>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
