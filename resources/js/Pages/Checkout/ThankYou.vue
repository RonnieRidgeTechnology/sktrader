<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CheckCircle, Package } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  title: { type: String, default: 'Thank You' },
  order: {
    type: Object,
    required: true,
  },
});

function formatPrice(value) {
  const cur = props.order?.currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), {
    style: 'currency',
    currency: cur,
    minimumFractionDigits: 2,
  }).format(value);
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-16 sm:py-24 md:py-32">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-2xl text-center">
        <div class="animate-scale-in inline-flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-luxe-gold shadow-luxe backdrop-blur-xl">
          <CheckCircle class="h-12 w-12" stroke-width="2" />
        </div>
        <h1 class="mt-8 font-display text-3xl font-semibold tracking-tight text-luxe-pearl sm:text-4xl">
          Order confirmed
        </h1>
        <p class="mt-4 text-lg text-luxe-pearl/80">
          Thank you, <strong class="text-luxe-pearl">{{ order.guest_name }}</strong>. We've received your order and will process it shortly.
        </p>
        <p class="mt-2 text-luxe-mist">
          Order number: <strong class="font-mono text-luxe-pearl">{{ order.number }}</strong>
        </p>

        <div class="mt-12 rounded-3xl border border-white/10 bg-white/5 p-6 text-left shadow-luxe backdrop-blur-xl sm:p-8">
          <h2 class="flex items-center gap-2 font-display text-lg font-semibold text-luxe-pearl">
            <Package class="h-5 w-5 text-luxe-gold" stroke-width="2" />
            Order summary
          </h2>
          <ul class="mt-6 space-y-3 border-b border-white/10 pb-6">
            <li
              v-for="(line, idx) in order.items"
              :key="idx"
              class="flex justify-between gap-4 text-sm"
            >
              <span class="text-luxe-pearl">{{ line.product_name }} × {{ line.quantity }}</span>
              <span class="font-medium text-luxe-pearl">{{ formatPrice(line.line_total) }}</span>
            </li>
          </ul>
          <div class="flex items-center justify-between pt-4 text-lg">
            <span class="font-medium text-luxe-mist">Total</span>
            <span class="font-display text-2xl font-semibold text-luxe-pearl">{{ formatPrice(order.total) }}</span>
          </div>
          <p v-if="order.payment_method === 'cod'" class="mt-4 text-sm text-luxe-pearl/80">
            Cash on Delivery (COD). You will pay when your order is delivered.
          </p>
          <p v-else-if="order.payment_method === 'zynlepay'" class="mt-4 text-sm text-luxe-pearl/80">
            Paid online with ZynlePay. Thank you for your payment.
          </p>
          <p v-else-if="order.payment_method === 'paypal'" class="mt-4 text-sm text-luxe-pearl/80">
            Paid with PayPal. Thank you for your payment.
          </p>
          <p v-else class="mt-4 text-sm text-luxe-pearl/80">
            We have recorded your order. You will receive updates by email.
          </p>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
          <Link
            :href="route('products')"
            class="luxe-btn luxe-btn-primary"
          >
            Continue shopping
          </Link>
          <Link
            :href="route('home')"
            class="luxe-btn luxe-btn-ghost"
          >
            Back to home
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
