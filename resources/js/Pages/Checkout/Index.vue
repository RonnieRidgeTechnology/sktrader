<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ShoppingBag, MapPin, CreditCard } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Checkout' },
  cart: {
    type: Object,
    default: () => ({ items: [], count: 0, subtotal: 0 }),
  },
  payment_methods: { type: Array, default: () => [] },
  currency: { type: String, default: 'ZMW' },
  user: { type: Object, default: null },
});

const items = computed(() => props.cart?.items || []);
const subtotal = computed(() => props.cart?.subtotal ?? 0);

function formatPrice(value) {
  return new Intl.NumberFormat('en-ZM', {
    style: 'currency',
    currency: 'ZMW',
    minimumFractionDigits: 2,
  }).format(value);
}

const form = useForm({
  payment_method: '',
  guest_name: props.user?.name ?? '',
  guest_email: props.user?.email ?? '',
  guest_phone: '',
  address_line_1: '',
  address_line_2: '',
  city: '',
  region: '',
  postal_code: '',
  country: 'Zambia',
  order_notes: '',
});

const paymentHint = computed(() => {
  const m = form.payment_method;
  if (m === 'zynlepay') return 'You will complete payment on the next step (ZynlePay).';
  if (m === 'paypal') return 'You will be redirected to PayPal to pay securely.';
  return 'Pay with cash when your order is delivered (COD).';
});

function firstError(key) {
  const v = form.errors?.[key];
  return Array.isArray(v) ? v[0] : v;
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-editorial-ink/10 bg-editorial-cream py-12 sm:py-16 md:py-24">
      <div class="mx-auto min-w-0 max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
          <Link
            :href="route('cart')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-editorial-coral transition hover:text-editorial-coral/80"
          >
            ← Back to cart
          </Link>
          <h1 class="mt-4 font-editorial text-3xl font-bold tracking-tight text-editorial-ink sm:text-4xl">
            {{ title }}
          </h1>
          <p class="mt-2 text-editorial-slate">Choose how you’d like to pay. All prices are in {{ currency }}.</p>
        </div>

        <form
          @submit.prevent="form.post(route('checkout.store'))"
          class="grid gap-10 lg:grid-cols-5"
        >
          <div class="lg:col-span-3 space-y-8">
            <!-- Payment method -->
            <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 shadow-premium sm:p-8">
              <h2 class="flex items-center gap-2 font-editorial text-xl font-semibold text-editorial-ink">
                <CreditCard class="h-5 w-5 text-editorial-coral" stroke-width="2" />
                Payment method
              </h2>
              <div class="mt-4 space-y-3">
                <label
                  v-for="pm in payment_methods"
                  :key="pm.id"
                  class="flex cursor-pointer items-start gap-4 rounded-xl border-2 p-4 transition"
                  :class="form.payment_method === pm.id ? 'border-editorial-coral bg-editorial-coral/5' : 'border-editorial-ink/10 hover:border-editorial-ink/20'"
                >
                  <input
                    v-model="form.payment_method"
                    type="radio"
                    :value="pm.id"
                    class="mt-1 h-4 w-4 border-editorial-ink/30 text-editorial-coral focus:ring-editorial-coral"
                  />
                  <div>
                    <span class="font-medium text-editorial-ink">{{ pm.label }}</span>
                    <p class="mt-0.5 text-sm text-editorial-slate">{{ pm.description }}</p>
                  </div>
                </label>
              </div>
              <p v-if="firstError('payment_method')" class="mt-2 text-sm text-red-600">{{ firstError('payment_method') }}</p>
            </div>

            <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 shadow-premium sm:p-8">
              <h2 class="flex items-center gap-2 font-editorial text-xl font-semibold text-editorial-ink">
                <MapPin class="h-5 w-5 text-editorial-coral" stroke-width="2" />
                Contact & delivery
              </h2>
              <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2 sm:grid-cols-2 sm:grid">
                  <div>
                    <label for="guest_name" class="block text-sm font-medium text-editorial-ink">Full name *</label>
                    <input
                      id="guest_name"
                      v-model="form.guest_name"
                      type="text"
                      required
                      class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                      placeholder="Your name"
                    />
                    <p v-if="firstError('guest_name')" class="mt-1 text-sm text-red-600">{{ firstError('guest_name') }}</p>
                  </div>
                  <div>
                    <label for="guest_email" class="block text-sm font-medium text-editorial-ink">Email *</label>
                    <input
                      id="guest_email"
                      v-model="form.guest_email"
                      type="email"
                      required
                      class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                      placeholder="you@example.com"
                    />
                    <p v-if="firstError('guest_email')" class="mt-1 text-sm text-red-600">{{ firstError('guest_email') }}</p>
                  </div>
                </div>
                <div class="sm:col-span-2">
                  <label for="guest_phone" class="block text-sm font-medium text-editorial-ink">Phone *</label>
                  <input
                    id="guest_phone"
                    v-model="form.guest_phone"
                    type="tel"
                    required
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="+260 97 123 4567"
                  />
                  <p v-if="firstError('guest_phone')" class="mt-1 text-sm text-red-600">{{ firstError('guest_phone') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="address_line_1" class="block text-sm font-medium text-editorial-ink">Address line 1 *</label>
                  <input
                    id="address_line_1"
                    v-model="form.address_line_1"
                    type="text"
                    required
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Street, building, area"
                  />
                  <p v-if="firstError('address_line_1')" class="mt-1 text-sm text-red-600">{{ firstError('address_line_1') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="address_line_2" class="block text-sm font-medium text-editorial-ink">Address line 2</label>
                  <input
                    id="address_line_2"
                    v-model="form.address_line_2"
                    type="text"
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Apartment, suite, etc. (optional)"
                  />
                </div>
                <div>
                  <label for="city" class="block text-sm font-medium text-editorial-ink">City *</label>
                  <input
                    id="city"
                    v-model="form.city"
                    type="text"
                    required
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Lusaka"
                  />
                  <p v-if="firstError('city')" class="mt-1 text-sm text-red-600">{{ firstError('city') }}</p>
                </div>
                <div>
                  <label for="region" class="block text-sm font-medium text-editorial-ink">Province / Region</label>
                  <input
                    id="region"
                    v-model="form.region"
                    type="text"
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Lusaka"
                  />
                </div>
                <div>
                  <label for="postal_code" class="block text-sm font-medium text-editorial-ink">Postal code</label>
                  <input
                    id="postal_code"
                    v-model="form.postal_code"
                    type="text"
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="10101"
                  />
                </div>
                <div>
                  <label for="country" class="block text-sm font-medium text-editorial-ink">Country *</label>
                  <input
                    id="country"
                    v-model="form.country"
                    type="text"
                    required
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Zambia"
                  />
                  <p v-if="firstError('country')" class="mt-1 text-sm text-red-600">{{ firstError('country') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="order_notes" class="block text-sm font-medium text-editorial-ink">Order notes</label>
                  <textarea
                    id="order_notes"
                    v-model="form.order_notes"
                    rows="3"
                    class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                    placeholder="Delivery instructions, preferred time, etc."
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="lg:col-span-2">
            <div class="sticky top-24 rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 shadow-premium sm:p-8">
              <h2 class="flex items-center gap-2 font-editorial text-xl font-semibold text-editorial-ink">
                <CreditCard class="h-5 w-5 text-editorial-coral" stroke-width="2" />
                Order summary
              </h2>
              <ul class="mt-6 space-y-4 border-b border-editorial-ink/10 pb-6">
                <li
                  v-for="item in items"
                  :key="item.product_id"
                  class="flex justify-between gap-4 text-sm"
                >
                  <span class="text-editorial-ink">{{ item.name }} × {{ item.quantity }}</span>
                  <span class="font-medium text-editorial-ink">{{ formatPrice(item.price * item.quantity) }}</span>
                </li>
              </ul>
              <div class="flex items-center justify-between pt-4 text-lg">
                <span class="font-medium text-editorial-slate">Subtotal</span>
                <span class="font-editorial text-2xl font-bold text-editorial-ink">{{ formatPrice(subtotal) }}</span>
              </div>
              <p class="mt-2 text-xs text-editorial-slate">
                {{ paymentHint }}
              </p>
              <button
                type="submit"
                :disabled="form.processing"
                class="mt-6 w-full min-h-[52px] border-2 border-editorial-ink bg-editorial-ink px-6 py-3.5 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90 disabled:opacity-70"
              >
                {{ form.processing ? 'Placing order…' : 'Place order' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </AppLayout>
</template>
