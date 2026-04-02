<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CreditCard } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const page = usePage();
const flashError = computed(() => page.props.flash?.error ?? null);

const props = defineProps({
  title: { type: String, default: 'Payment cancelled' },
  order: {
    type: Object,
    default: () => ({}),
  },
});

function formatPrice(value) {
  const cur = props.order?.currency || 'USD';
  try {
    return new Intl.NumberFormat(localeForIsoCurrency(cur), {
      style: 'currency',
      currency: cur,
      minimumFractionDigits: 2,
    }).format(value);
  } catch {
    return `${cur} ${Number(value).toFixed(2)}`;
  }
}
</script>

<template>
  <AppLayout>
    <section class="relative border-t border-editorial-ink/10 bg-editorial-cream py-12 sm:py-16 md:py-24">
      <div class="mx-auto min-w-0 max-w-xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border-2 border-editorial-ink/10 bg-white p-8 shadow-premium">
          <div class="flex items-center gap-3 text-editorial-coral">
            <CreditCard class="h-8 w-8" stroke-width="2" />
            <h1 class="font-editorial text-2xl font-bold text-editorial-ink sm:text-3xl">
              {{ title }}
            </h1>
          </div>
          <p v-if="flashError" class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-200">
            {{ flashError }}
          </p>
          <p class="mt-4 text-editorial-slate">
            You left PayPal before completing payment. Order
            <span class="font-mono font-semibold text-editorial-ink">{{ order?.number }}</span>
            is still pending for
            <strong class="text-editorial-ink">{{ formatPrice(order?.total ?? 0) }}</strong>.
          </p>
          <p class="mt-3 text-sm text-editorial-slate">
            You can try PayPal again from checkout, or contact us if you need help.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <Link
              :href="route('checkout.paypal.start', { order: order?.number })"
              class="inline-flex min-h-[48px] items-center justify-center border-2 border-editorial-ink bg-editorial-ink px-6 py-3 font-semibold text-white shadow-premium transition hover:bg-editorial-ink/90"
            >
              Try PayPal again
            </Link>
            <Link
              :href="route('home')"
              class="inline-flex min-h-[48px] items-center justify-center border-2 border-editorial-ink/20 bg-transparent px-6 py-3 font-semibold text-editorial-ink transition hover:bg-editorial-ink/5"
            >
              Back to home
            </Link>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
