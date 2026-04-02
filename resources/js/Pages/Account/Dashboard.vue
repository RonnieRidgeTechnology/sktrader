<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AccountLayout from '@/Layouts/AccountLayout.vue';
import { ShoppingBag, Package, ChevronRight } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  user: { type: Object, required: true },
  recent_orders: { type: Array, default: () => [] },
  orders_count: { type: Number, default: 0 },
});

function formatDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso);
  return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}
function formatCurrency(amount, currency = 'USD') {
  const cur = currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), { style: 'currency', currency: cur, minimumFractionDigits: 2 }).format(amount);
}
function statusLabel(status) {
  const labels = { pending: 'Pending', confirmed: 'Confirmed', processing: 'Processing', shipped: 'Shipped', delivered: 'Delivered', cancelled: 'Cancelled' };
  return labels[status] || status;
}
function statusClass(status) {
  const map = {
    pending: 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
    confirmed: 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300',
    processing: 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
    shipped: 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-300',
    delivered: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
    cancelled: 'bg-zinc-200 text-zinc-700 dark:bg-zinc-600 dark:text-zinc-300',
  };
  return map[status] || 'bg-zinc-100 text-zinc-700';
}
</script>

<template>
  <AccountLayout>
    <Head :title="props.title || 'My Account'" />
    <div class="space-y-8">
      <div>
        <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white sm:text-3xl">
          Welcome back, {{ user.name }}
        </h1>
        <p class="mt-1 text-editorial-slate dark:text-zinc-400">
          {{ user.email }}
        </p>
      </div>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <Link
          :href="route('account.orders.index')"
          class="flex items-center gap-4 rounded-xl border-2 border-editorial-ink/10 bg-white p-5 shadow-sm transition hover:border-editorial-coral/30 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-editorial-coral/30"
        >
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-editorial-coral/15 text-editorial-coral">
            <ShoppingBag class="h-6 w-6" stroke-width="2" />
          </div>
          <div class="min-w-0">
            <p class="text-2xl font-bold text-editorial-ink dark:text-white">{{ orders_count }}</p>
            <p class="text-sm font-medium text-editorial-slate dark:text-zinc-400">Total orders</p>
          </div>
          <ChevronRight class="h-5 w-5 shrink-0 text-editorial-slate" />
        </Link>
        <Link
          :href="route('profile.edit')"
          class="flex items-center gap-4 rounded-xl border-2 border-editorial-ink/10 bg-white p-5 shadow-sm transition hover:border-editorial-coral/30 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-editorial-coral/30"
        >
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-editorial-coral/15 text-editorial-coral">
            <Package class="h-6 w-6" stroke-width="2" />
          </div>
          <div class="min-w-0">
            <p class="text-sm font-semibold text-editorial-ink dark:text-white">Profile & security</p>
            <p class="text-sm text-editorial-slate dark:text-zinc-400">Update your details</p>
          </div>
          <ChevronRight class="h-5 w-5 shrink-0 text-editorial-slate" />
        </Link>
      </div>

      <div class="rounded-xl border-2 border-editorial-ink/10 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-editorial-ink/10 px-5 py-4 dark:border-zinc-600">
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink dark:text-white">Recent orders</h2>
          <Link
            v-if="recent_orders.length"
            :href="route('account.orders.index')"
            class="text-sm font-medium text-editorial-coral hover:underline"
          >
            View all orders
          </Link>
        </div>
        <div v-if="recent_orders.length" class="divide-y divide-editorial-ink/10 dark:divide-zinc-600">
          <Link
            v-for="order in recent_orders"
            :key="order.id"
            :href="route('account.orders.show', order.id)"
            class="flex flex-wrap items-center gap-4 px-5 py-4 transition hover:bg-editorial-ink/5 dark:hover:bg-zinc-700/30"
          >
            <div class="min-w-0 flex-1">
              <p class="font-semibold text-editorial-ink dark:text-white">Order {{ order.number }}</p>
              <p class="text-sm text-editorial-slate dark:text-zinc-400">{{ formatDate(order.created_at) }} · {{ order.items_count }} item(s)</p>
            </div>
            <span
              :class="['rounded-full px-2.5 py-1 text-xs font-semibold', statusClass(order.status)]"
            >
              {{ statusLabel(order.status) }}
            </span>
            <p class="w-full text-right text-sm font-semibold text-editorial-ink dark:text-white sm:w-auto sm:text-base">
              {{ formatCurrency(order.total, order.currency) }}
            </p>
            <ChevronRight class="h-5 w-5 shrink-0 text-editorial-slate" />
          </Link>
        </div>
        <div v-else class="px-5 py-12 text-center">
          <ShoppingBag class="mx-auto h-12 w-12 text-editorial-slate/50 dark:text-zinc-500" stroke-width="1.5" />
          <p class="mt-3 text-sm font-medium text-editorial-slate dark:text-zinc-400">No orders yet</p>
          <Link
            :href="route('products')"
            class="mt-2 inline-block text-sm font-semibold text-editorial-coral hover:underline"
          >
            Start shopping →
          </Link>
        </div>
      </div>
    </div>
  </AccountLayout>
</template>
