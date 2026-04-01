<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CheckCircle, Package } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Thank You' },
  order: {
    type: Object,
    required: true,
  },
});

function formatPrice(value) {
  return new Intl.NumberFormat('en-ZM', {
    style: 'currency',
    currency: props.order?.currency || 'ZMW',
    minimumFractionDigits: 2,
  }).format(value);
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-editorial-ink/10 bg-editorial-cream py-16 sm:py-24 md:py-32">
      <div class="mx-auto min-w-0 max-w-2xl px-4 text-center sm:px-6 lg:px-8">
        <div class="animate-scale-in inline-flex h-20 w-20 items-center justify-center rounded-full border-2 border-editorial-sage bg-editorial-sage/10 text-editorial-sage">
          <CheckCircle class="h-12 w-12" stroke-width="2" />
        </div>
        <h1 class="mt-8 font-editorial text-3xl font-bold tracking-tight text-editorial-ink sm:text-4xl">
          Order confirmed
        </h1>
        <p class="mt-4 text-lg text-editorial-slate">
          Thank you, <strong class="text-editorial-ink">{{ order.guest_name }}</strong>. We've received your order and will process it shortly.
        </p>
        <p class="mt-2 text-editorial-slate">
          Order number: <strong class="font-mono text-editorial-ink">{{ order.number }}</strong>
        </p>

        <div class="mt-12 rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 text-left shadow-premium sm:p-8">
          <h2 class="flex items-center gap-2 font-editorial text-lg font-semibold text-editorial-ink">
            <Package class="h-5 w-5 text-editorial-coral" stroke-width="2" />
            Order summary
          </h2>
          <ul class="mt-6 space-y-3 border-b border-editorial-ink/10 pb-6">
            <li
              v-for="(line, idx) in order.items"
              :key="idx"
              class="flex justify-between gap-4 text-sm"
            >
              <span class="text-editorial-ink">{{ line.product_name }} × {{ line.quantity }}</span>
              <span class="font-medium text-editorial-ink">{{ formatPrice(line.line_total) }}</span>
            </li>
          </ul>
          <div class="flex items-center justify-between pt-4 text-lg">
            <span class="font-medium text-editorial-slate">Total</span>
            <span class="font-editorial text-2xl font-bold text-editorial-ink">{{ formatPrice(order.total) }}</span>
          </div>
          <p v-if="order.payment_method === 'cod'" class="mt-4 text-sm text-editorial-slate">
            Cash on Delivery (COD). You will pay when your order is delivered.
          </p>
          <p v-else-if="order.payment_method === 'zynlepay'" class="mt-4 text-sm text-editorial-slate">
            Paid online with ZynlePay. Thank you for your payment.
          </p>
          <p v-else-if="order.payment_method === 'paypal'" class="mt-4 text-sm text-editorial-slate">
            Paid with PayPal. Thank you for your payment.
          </p>
          <p v-else class="mt-4 text-sm text-editorial-slate">
            We have recorded your order. You will receive updates by email.
          </p>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
          <Link
            :href="route('products')"
            class="inline-flex min-h-[48px] items-center justify-center border-2 border-editorial-ink bg-editorial-ink px-6 py-3 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90"
          >
            Continue shopping
          </Link>
          <Link
            :href="route('home')"
            class="inline-flex min-h-[48px] items-center justify-center border-2 border-editorial-ink/20 bg-transparent px-6 py-3 font-semibold text-editorial-ink transition hover:bg-editorial-ink/5"
          >
            Back to home
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
