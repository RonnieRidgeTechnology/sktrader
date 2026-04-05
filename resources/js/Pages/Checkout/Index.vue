<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ShoppingBag, MapPin, CreditCard } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  title: { type: String, default: 'Checkout' },
  cart: {
    type: Object,
    default: () => ({ items: [], count: 0, subtotal: 0 }),
  },
  payment_methods: { type: Array, default: () => [] },
  currency: { type: String, default: 'USD' },
  user: { type: Object, default: null },
  bank_account_details: { type: String, default: '' },
});

const items = computed(() => props.cart?.items || []);
const subtotal = computed(() => props.cart?.subtotal ?? 0);

function formatPrice(value) {
  const cur = props.currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), {
    style: 'currency',
    currency: cur,
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
  payment_proof: null,
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
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-12 sm:py-16 md:py-24">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-6xl">
        <div class="mb-10">
          <Link
            :href="route('cart')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-luxe-gold transition hover:opacity-90"
          >
            ← Back to cart
          </Link>
          <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight text-luxe-pearl sm:text-4xl">
            {{ title }}
          </h1>
          <p class="mt-2 text-luxe-pearl/80">Choose how you’d like to pay. All prices are in {{ currency }}.</p>
        </div>

        <form
          @submit.prevent="form.post(route('checkout.store'))"
          class="grid gap-10 lg:grid-cols-5"
        >
          <div class="lg:col-span-3 space-y-8">
            <!-- Payment method -->
            <div class="luxe-surface-strong rounded-3xl p-6 sm:p-8">
              <h2 class="flex items-center gap-2 font-display text-xl font-semibold text-luxe-pearl">
                <CreditCard class="h-5 w-5 text-luxe-gold" stroke-width="2" />
                Payment method
              </h2>
              <div class="mt-4 space-y-3">
                <label
                  v-for="pm in payment_methods"
                  :key="pm.id"
                  class="flex cursor-pointer flex-col gap-3 rounded-3xl border p-4 transition xs:flex-row xs:items-start xs:gap-4"
                  :class="form.payment_method === pm.id ? 'border-luxe-gold/40 bg-luxe-gold/10' : 'border-white/10 hover:border-white/15 hover:bg-white/5'"
                >
                  <input
                    v-model="form.payment_method"
                    type="radio"
                    :value="pm.id"
                    class="h-4 w-4 shrink-0 border-white/20 text-luxe-gold focus:ring-luxe-gold/60 xs:mt-1"
                  />
                  <div class="min-w-0">
                    <span class="font-medium text-luxe-pearl">{{ pm.label }}</span>
                    <p class="mt-0.5 text-sm text-luxe-pearl/80">{{ pm.description }}</p>
                  </div>
                </label>
              </div>
              <div v-if="form.payment_method === 'bank_transfer'" class="mt-6 p-5 rounded-2xl bg-black/40 border border-white/10">
                   <h3 class="font-semibold text-luxe-pearl mb-2 text-sm">Bank Transfer Instructions</h3>
                   <div class="text-sm text-luxe-pearl/80 mb-4 whitespace-pre-wrap">{{ bank_account_details }}</div>
                   <label for="payment_proof" class="block text-sm font-medium text-luxe-pearl">Upload Payment Proof (Required)</label>
                   <input
                        id="payment_proof"
                        type="file"
                        accept="image/*"
                        @change="e => form.payment_proof = e.target.files[0]"
                        class="mt-2 block w-full text-sm text-luxe-pearl file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-luxe-gold file:text-black hover:file:bg-luxe-gold/90"
                   />
                   <p v-if="firstError('payment_proof')" class="mt-2 text-sm text-red-600">{{ firstError('payment_proof') }}</p>
              </div>
              <p v-if="firstError('payment_method')" class="mt-2 text-sm text-red-600">{{ firstError('payment_method') }}</p>
            </div>

            <div class="luxe-surface-strong rounded-3xl p-6 shadow-luxe sm:p-8">
              <h2 class="flex items-center gap-2 font-display text-xl font-semibold text-luxe-pearl">
                <MapPin class="h-5 w-5 text-luxe-gold" stroke-width="2" />
                Contact & delivery
              </h2>
              <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2 sm:grid-cols-2 sm:grid">
                  <div>
                    <label for="guest_name" class="block text-sm font-medium text-luxe-pearl">Full name *</label>
                    <input
                      id="guest_name"
                      v-model="form.guest_name"
                      type="text"
                      required
                      class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                      placeholder="Your name"
                    />
                    <p v-if="firstError('guest_name')" class="mt-1 text-sm text-red-600">{{ firstError('guest_name') }}</p>
                  </div>
                  <div>
                    <label for="guest_email" class="block text-sm font-medium text-luxe-pearl">Email *</label>
                    <input
                      id="guest_email"
                      v-model="form.guest_email"
                      type="email"
                      required
                      class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                      placeholder="you@example.com"
                    />
                    <p v-if="firstError('guest_email')" class="mt-1 text-sm text-red-600">{{ firstError('guest_email') }}</p>
                  </div>
                </div>
                <div class="sm:col-span-2">
                  <label for="guest_phone" class="block text-sm font-medium text-luxe-pearl">Phone *</label>
                  <input
                    id="guest_phone"
                    v-model="form.guest_phone"
                    type="tel"
                    required
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="+260 97 123 4567"
                  />
                  <p v-if="firstError('guest_phone')" class="mt-1 text-sm text-red-600">{{ firstError('guest_phone') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="address_line_1" class="block text-sm font-medium text-luxe-pearl">Address line 1 *</label>
                  <input
                    id="address_line_1"
                    v-model="form.address_line_1"
                    type="text"
                    required
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Street, building, area"
                  />
                  <p v-if="firstError('address_line_1')" class="mt-1 text-sm text-red-600">{{ firstError('address_line_1') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="address_line_2" class="block text-sm font-medium text-luxe-pearl">Address line 2</label>
                  <input
                    id="address_line_2"
                    v-model="form.address_line_2"
                    type="text"
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Apartment, suite, etc. (optional)"
                  />
                </div>
                <div>
                  <label for="city" class="block text-sm font-medium text-luxe-pearl">City *</label>
                  <input
                    id="city"
                    v-model="form.city"
                    type="text"
                    required
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Lusaka"
                  />
                  <p v-if="firstError('city')" class="mt-1 text-sm text-red-600">{{ firstError('city') }}</p>
                </div>
                <div>
                  <label for="region" class="block text-sm font-medium text-luxe-pearl">Province / Region</label>
                  <input
                    id="region"
                    v-model="form.region"
                    type="text"
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Lusaka"
                  />
                </div>
                <div>
                  <label for="postal_code" class="block text-sm font-medium text-luxe-pearl">Postal code</label>
                  <input
                    id="postal_code"
                    v-model="form.postal_code"
                    type="text"
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="10101"
                  />
                </div>
                <div>
                  <label for="country" class="block text-sm font-medium text-luxe-pearl">Country *</label>
                  <input
                    id="country"
                    v-model="form.country"
                    type="text"
                    required
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Zambia"
                  />
                  <p v-if="firstError('country')" class="mt-1 text-sm text-red-600">{{ firstError('country') }}</p>
                </div>
                <div class="sm:col-span-2">
                  <label for="order_notes" class="block text-sm font-medium text-luxe-pearl">Order notes</label>
                  <textarea
                    id="order_notes"
                    v-model="form.order_notes"
                    rows="3"
                    class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                    placeholder="Delivery instructions, preferred time, etc."
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="lg:col-span-2">
            <div class="sticky top-24 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-luxe backdrop-blur-xl sm:p-8">
              <h2 class="flex items-center gap-2 font-display text-xl font-semibold text-luxe-pearl">
                <CreditCard class="h-5 w-5 text-luxe-gold" stroke-width="2" />
                Order summary
              </h2>
              <ul class="mt-6 space-y-4 border-b border-white/10 pb-6">
                <li
                  v-for="item in items"
                  :key="item.product_id"
                  class="flex justify-between gap-4 text-sm"
                >
                  <span class="text-luxe-pearl">{{ item.name }} × {{ item.quantity }}</span>
                  <span class="font-medium text-luxe-pearl">{{ formatPrice(item.price * item.quantity) }}</span>
                </li>
              </ul>
              <div class="flex items-center justify-between pt-4 text-lg">
                <span class="font-medium text-luxe-mist">Subtotal</span>
                <span class="font-display text-2xl font-semibold text-luxe-pearl">{{ formatPrice(subtotal) }}</span>
              </div>
              <p class="mt-2 text-xs text-luxe-pearl/80">
                {{ paymentHint }}
              </p>
              <button
                type="submit"
                :disabled="form.processing"
                class="mt-6 w-full luxe-btn luxe-btn-primary disabled:opacity-70"
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
