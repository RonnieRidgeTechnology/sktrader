<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Smartphone, CheckCircle, CreditCard } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'Pay with ZynlePay' },
  zynlepay_channel: { type: String, default: 'momo' },
  order: {
    type: Object,
    required: true,
    default: () => ({ number: '', total: 0, currency: 'ZMW', guest_phone: '' }),
  },
});

const isCard = computed(() => (props.zynlepay_channel || '').toLowerCase() === 'card');

const page = usePage();
const flashSuccess = computed(() => page.flash?.success);
const flashError = computed(() => page.flash?.error);

function formatPrice(value) {
  return new Intl.NumberFormat('en-ZM', {
    style: 'currency',
    currency: props.order?.currency || 'ZMW',
    minimumFractionDigits: 2,
  }).format(value);
}

const form = useForm({
  order_number: props.order?.number ?? '',
  phone: props.order?.guest_phone ?? '',
});

function firstError(key) {
  const v = form.errors?.[key];
  return Array.isArray(v) ? v[0] : v;
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-editorial-ink/10 bg-editorial-cream py-12 sm:py-16 md:py-24">
      <div class="mx-auto min-w-0 max-w-xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <Link
            :href="route('checkout')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-editorial-coral transition hover:text-editorial-coral/80"
          >
            ← Back to checkout
          </Link>
          <h1 class="mt-4 font-editorial text-3xl font-bold tracking-tight text-editorial-ink sm:text-4xl">
            {{ title }}
          </h1>
          <p class="mt-2 text-editorial-slate">
            Complete your order <strong>{{ order?.number }}</strong> by paying {{ isCard ? 'by card' : 'with Mobile Money' }} (ZynlePay). Amount due: <strong>{{ formatPrice(order?.total ?? 0) }}</strong>.
          </p>
        </div>

        <div v-if="flashSuccess" class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
          {{ flashSuccess }}
        </div>
        <div v-if="flashError" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
          {{ flashError }}
        </div>

        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-6 shadow-premium sm:p-8">
          <h2 class="flex items-center gap-2 font-editorial text-xl font-semibold text-editorial-ink">
            <CreditCard v-if="isCard" class="h-5 w-5 text-editorial-coral" stroke-width="2" />
            <Smartphone v-else class="h-5 w-5 text-editorial-coral" stroke-width="2" />
            {{ isCard ? 'Pay by card' : 'Enter your mobile number' }}
          </h2>
          <p v-if="isCard" class="mt-2 text-sm text-editorial-slate">
            Click below to proceed to secure card payment. You will complete payment on the next step.
          </p>
          <p v-else class="mt-2 text-sm text-editorial-slate">
            You will receive a prompt on your phone to approve the payment. Use the number linked to your Mobile Money account (e.g. 09XX XXX XXX or +260 9XX XXX XXX).
          </p>

          <form
            v-if="!flashSuccess || flashError"
            @submit.prevent="form.post(route('checkout.initiate-payment'))"
            class="mt-6 space-y-4"
          >
            <input v-model="form.order_number" type="hidden" name="order_number" />
            <div v-if="!isCard">
              <label for="phone" class="block text-sm font-medium text-editorial-ink">Mobile number *</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                required
                class="mt-2 block w-full border-2 border-editorial-ink/15 bg-white px-4 py-3 text-editorial-ink focus:border-editorial-coral focus:ring-2 focus:ring-editorial-coral/20"
                placeholder="+260 97 123 4567"
              />
              <p v-if="firstError('phone')" class="mt-1 text-sm text-red-600">{{ firstError('phone') }}</p>
            </div>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full min-h-[52px] border-2 border-editorial-ink bg-editorial-ink px-6 py-3.5 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90 disabled:opacity-70"
            >
              {{ form.processing ? 'Initiating…' : (isCard ? 'Proceed to pay by card' : 'Send payment request to my phone') }}
            </button>
          </form>

          <div v-else class="mt-6 flex flex-col items-center gap-4 rounded-xl border border-editorial-ink/10 bg-editorial-cream/50 p-6">
            <CheckCircle class="h-12 w-12 text-green-600" stroke-width="2" />
            <p class="text-center text-sm font-medium text-editorial-ink">
              {{ isCard ? 'Complete payment on the next screen. Once done, click below to confirm.' : 'Check your phone and approve the payment. Once done, click below to confirm.' }}
            </p>
            <Link
              :href="route('checkout.payment-status', { order: order?.number })"
              class="inline-flex min-h-[48px] items-center justify-center rounded-xl border-2 border-editorial-coral bg-editorial-coral px-6 py-3 font-semibold text-white transition hover:bg-editorial-coral/90"
            >
              I’ve paid — check payment status
            </Link>
          </div>
        </div>

        <p class="mt-6 text-center text-xs text-editorial-slate">
          All amounts are in {{ order?.currency ?? 'ZMW' }}. Powered by ZynlePay.
        </p>
      </div>
    </section>
  </AppLayout>
</template>
