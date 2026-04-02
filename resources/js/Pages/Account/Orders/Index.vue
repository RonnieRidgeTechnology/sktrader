<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AccountLayout from '@/Layouts/AccountLayout.vue';
import { ShoppingBag, ChevronRight } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  orders: { type: Object, required: true },
  statusFilter: { type: String, default: '' },
  statusOptions: { type: Object, default: () => ({}) },
});

const ordersList = props.orders?.data ?? [];
const pagination = {
  current_page: props.orders?.current_page ?? 1,
  last_page: props.orders?.last_page ?? 1,
  per_page: props.orders?.per_page ?? 10,
  total: props.orders?.total ?? 0,
};

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}
function formatCurrency(amount, currency = 'USD') {
  const cur = currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), { style: 'currency', currency: cur, minimumFractionDigits: 2 }).format(amount);
}
function statusLabel(status) {
  return props.statusOptions[status] || status;
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
function filterByStatus(status) {
  router.get(route('account.orders.index'), status ? { status } : {}, { preserveState: true });
}
</script>

<template>
  <AccountLayout>
    <Head :title="title || 'My Orders'" />
    <div class="space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
          My Orders
        </h1>
        <div class="flex flex-wrap gap-2">
          <button
            type="button"
            class="rounded-lg px-3 py-1.5 text-sm font-medium transition"
            :class="!statusFilter ? 'bg-editorial-coral text-white' : 'bg-editorial-ink/10 text-editorial-slate hover:bg-editorial-ink/15 dark:bg-zinc-700 dark:text-zinc-300'"
            @click="filterByStatus('')"
          >
            All
          </button>
          <button
            v-for="(label, value) in statusOptions"
            :key="value"
            type="button"
            class="rounded-lg px-3 py-1.5 text-sm font-medium transition"
            :class="statusFilter === value ? 'bg-editorial-coral text-white' : 'bg-editorial-ink/10 text-editorial-slate hover:bg-editorial-ink/15 dark:bg-zinc-700 dark:text-zinc-300'"
            @click="filterByStatus(value)"
          >
            {{ label }}
          </button>
        </div>
      </div>

      <div class="rounded-xl border-2 border-editorial-ink/10 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50">
        <div v-if="ordersList.length" class="divide-y divide-editorial-ink/10 dark:divide-zinc-600">
          <Link
            v-for="order in ordersList"
            :key="order.id"
            :href="route('account.orders.show', order.id)"
            class="flex flex-wrap items-center gap-4 px-5 py-4 transition hover:bg-editorial-ink/5 dark:hover:bg-zinc-700/30"
          >
            <div class="min-w-0 flex-1">
              <p class="font-semibold text-editorial-ink dark:text-white">Order {{ order.number }}</p>
              <p class="text-sm text-editorial-slate dark:text-zinc-400">{{ formatDate(order.created_at) }} · {{ order.items_count }} item(s)</p>
            </div>
            <span :class="['rounded-full px-2.5 py-1 text-xs font-semibold', statusClass(order.status)]">
              {{ statusLabel(order.status) }}
            </span>
            <p class="font-semibold text-editorial-ink dark:text-white">
              {{ formatCurrency(order.total, order.currency) }}
            </p>
            <ChevronRight class="h-5 w-5 shrink-0 text-editorial-slate" />
          </Link>
        </div>
        <div v-else class="px-5 py-16 text-center">
          <ShoppingBag class="mx-auto h-14 w-14 text-editorial-slate/40 dark:text-zinc-500" stroke-width="1.5" />
          <p class="mt-4 text-editorial-slate dark:text-zinc-400">No orders found.</p>
          <Link :href="route('products')" class="mt-3 inline-block font-semibold text-editorial-coral hover:underline">
            Browse products →
          </Link>
        </div>

        <!-- Pagination -->
        <div
          v-if="pagination.last_page > 1"
          class="flex flex-wrap items-center justify-between gap-2 border-t border-editorial-ink/10 px-5 py-4 dark:border-zinc-600"
        >
          <p class="text-sm text-editorial-slate dark:text-zinc-400">
            Page {{ pagination.current_page }} of {{ pagination.last_page }} ({{ pagination.total }} orders)
          </p>
          <div class="flex gap-2">
            <Link
              v-if="pagination.current_page > 1"
              :href="route('account.orders.index', { page: pagination.current_page - 1, ...(statusFilter ? { status: statusFilter } : {}) })"
              class="rounded-lg border border-editorial-ink/20 px-3 py-2 text-sm font-medium text-editorial-ink hover:bg-editorial-ink/5 dark:border-zinc-600 dark:text-zinc-300"
            >
              Previous
            </Link>
            <Link
              v-if="pagination.current_page < pagination.last_page"
              :href="route('account.orders.index', { page: pagination.current_page + 1, ...(statusFilter ? { status: statusFilter } : {}) })"
              class="rounded-lg border border-editorial-ink/20 px-3 py-2 text-sm font-medium text-editorial-ink hover:bg-editorial-ink/5 dark:border-zinc-600 dark:text-zinc-300"
            >
              Next
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AccountLayout>
</template>
