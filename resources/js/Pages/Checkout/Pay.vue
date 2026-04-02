<script setup>
import { computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Smartphone, CheckCircle, CreditCard } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  title: { type: String, default: 'Pay with ZynlePay' },
  zynlepay_channel: { type: String, default: 'momo' },
  order: {
    type: Object,
    required: true,
    default: () => ({ number: '', total: 0, currency: 'USD', guest_phone: '' }),
  },
});

const isCard = computed(() => (props.zynlepay_channel || '').toLowerCase() === 'card');

const page = usePage();
const flashSuccess = computed(() => page.flash?.success);
const flashError = computed(() => page.flash?.error);

function formatPrice(value) {
  const cur = props.order?.currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), {
    style: 'currency',
    currency: cur,
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
    <section class="relative border-t border-white/10 bg-luxe-obsidian py-12 sm:py-16 md:py-24">
      <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-60" aria-hidden="true" />
      <div class="relative luxe-container max-w-xl">
        <div class="mb-8">
          <Link
            :href="route('checkout')"
            class="inline-flex items-center gap-2 text-sm font-semibold text-luxe-gold transition hover:opacity-90"
          >
            ← Back to checkout
          </Link>
          <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight text-luxe-pearl sm:text-4xl">
            {{ title }}
          </h1>
          <p class="mt-2 text-luxe-pearl/80">
            Complete your order <strong>{{ order?.number }}</strong> by paying {{ isCard ? 'by card' : 'with Mobile Money' }} (ZynlePay). Amount due: <strong>{{ formatPrice(order?.total ?? 0) }}</strong>.
          </p>
        </div>

        <div v-if="flashSuccess" class="mb-6 rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-200">
          {{ flashSuccess }}
        </div>
        <div v-if="flashError" class="mb-6 rounded-3xl border border-red-400/20 bg-red-400/10 p-4 text-sm text-red-200">
          {{ flashError }}
        </div>

        <div class="luxe-surface-strong rounded-3xl p-6 sm:p-8">
          <h2 class="flex items-center gap-2 font-display text-xl font-semibold text-luxe-pearl">
            <CreditCard v-if="isCard" class="h-5 w-5 text-luxe-gold" stroke-width="2" />
            <Smartphone v-else class="h-5 w-5 text-luxe-gold" stroke-width="2" />
            {{ isCard ? 'Pay by card' : 'Enter your mobile number' }}
          </h2>
          <p v-if="isCard" class="mt-2 text-sm text-luxe-pearl/80">
            Click below to proceed to secure card payment. You will complete payment on the next step.
          </p>
          <p v-else class="mt-2 text-sm text-luxe-pearl/80">
            You will receive a prompt on your phone to approve the payment. Use the number linked to your Mobile Money account (e.g. 09XX XXX XXX or +260 9XX XXX XXX).
          </p>

          <form
            v-if="!flashSuccess || flashError"
            @submit.prevent="form.post(route('checkout.initiate-payment'))"
            class="mt-6 space-y-4"
          >
            <input v-model="form.order_number" type="hidden" name="order_number" />
            <div v-if="!isCard">
              <label for="phone" class="block text-sm font-medium text-luxe-pearl">Mobile number *</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                required
                class="mt-2 block w-full rounded-2xl border border-white/15 bg-black/30 px-4 py-3 text-luxe-pearl placeholder:text-luxe-mist/70 focus:border-luxe-gold/50 focus:ring-1 focus:ring-luxe-gold/50"
                placeholder="+260 97 123 4567"
              />
              <p v-if="firstError('phone')" class="mt-1 text-sm text-red-600">{{ firstError('phone') }}</p>
            </div>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full luxe-btn luxe-btn-primary disabled:opacity-70"
            >
              {{ form.processing ? 'Initiating…' : (isCard ? 'Proceed to pay by card' : 'Send payment request to my phone') }}
            </button>
          </form>

          <div v-else class="mt-6 flex flex-col items-center gap-4 rounded-3xl border border-white/10 bg-white/5 p-6">
            <CheckCircle class="h-12 w-12 text-emerald-300" stroke-width="2" />
            <p class="text-center text-sm font-medium text-luxe-pearl">
              {{ isCard ? 'Complete payment on the next screen. Once done, click below to confirm.' : 'Check your phone and approve the payment. Once done, click below to confirm.' }}
            </p>
            <Link
              :href="route('checkout.payment-status', { order: order?.number })"
              class="luxe-btn luxe-btn-primary"
            >
              I’ve paid — check payment status
            </Link>
          </div>
        </div>

        <p class="mt-6 text-center text-xs text-luxe-mist">
          All amounts are in {{ order?.currency ?? 'USD' }}. Powered by ZynlePay.
        </p>
      </div>
    </section>
  </AppLayout>
</template>
