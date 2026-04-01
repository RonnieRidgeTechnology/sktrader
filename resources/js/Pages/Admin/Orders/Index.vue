<script setup>
import { computed, inject } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ShoppingBag, Eye } from 'lucide-vue-next';

function fallbackRoute(name, ...params) {
  if (typeof window !== 'undefined' && typeof window.route === 'function') {
    return params.length === 0 && name === undefined ? window.route() : window.route(name, ...params);
  }
  return params.length === 0 && name === undefined ? { current: null } : '#';
}
const route = inject('route', fallbackRoute);
const page = usePage();

const props = defineProps({
  /** Duplicate keys: read from usePage().props first (see rows computed). */
  adminOrderRows: { type: Array, default: undefined },
  orderIndexRows: { type: Array, default: undefined },
  adminOrderPagination: { type: Object, default: undefined },
  statusFilter: { type: String, default: null },
  statusOptions: { type: Object, default: undefined },
  ordersTotalAll: { type: Number, default: undefined },
});

/** Normalize list from Inertia (page.props is authoritative; avoid prop defaults that hide server data). */
function coerceRows(value) {
  if (Array.isArray(value)) return value;
  if (value && typeof value === 'object') {
    const vals = Object.values(value);
    if (vals.length && vals.every((x) => x && typeof x === 'object')) return vals;
  }
  return [];
}

const rows = computed(() => {
  const p = page.props;
  const candidates = [
    p.orderIndexRows,
    p.adminOrderRows,
    props.orderIndexRows,
    props.adminOrderRows,
    p.ordersPage?.data,
    p.orders?.data,
  ];
  for (const c of candidates) {
    const r = coerceRows(c);
    if (r.length > 0) return r;
  }
  for (const c of candidates) {
    const r = coerceRows(c);
    if (r.length === 0 && Array.isArray(c)) return c;
  }
  return [];
});

const paginationBar = computed(() => {
  const p = page.props.adminOrderPagination ?? props.adminOrderPagination ?? {};
  return {
    from: p.from ?? 0,
    to: p.to ?? 0,
    total: p.total ?? 0,
    prev_page_url: p.prev_page_url ?? null,
    next_page_url: p.next_page_url ?? null,
  };
});

const totalAllInDb = computed(() => Number(page.props.ordersTotalAll ?? props.ordersTotalAll ?? 0) || 0);
const statusFilterVal = computed(() => page.props.statusFilter ?? props.statusFilter ?? null);
const statusOpts = computed(() => {
  const o = page.props.statusOptions ?? props.statusOptions;
  return o && typeof o === 'object' && !Array.isArray(o) ? o : {};
});
const filterHidesOrders = computed(
  () => !!statusFilterVal.value && rows.value.length === 0 && totalAllInDb.value > 0
);

function formatPrice(value, currency = 'ZMW') {
  return new Intl.NumberFormat('en-ZM', { style: 'currency', currency, minimumFractionDigits: 2 }).format(value);
}

function formatDate(value) {
  if (value == null || value === '') return '—';
  const d = new Date(value);
  if (Number.isNaN(d.getTime())) return '—';
  return d.toLocaleDateString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
}

function statusClass(status) {
  const map = {
    pending: 'status-badge-warning',
    confirmed: 'status-badge-info',
    processing: 'status-badge-info',
    shipped: 'status-badge-info',
    delivered: 'status-badge-success',
    cancelled: 'status-badge-danger',
  };
  return map[status] || 'status-badge-neutral';
}

function setStatusFilter(status) {
  const url = typeof route === 'function' ? route('admin.orders.index') : '#';
  router.get(url, status ? { status } : {}, { preserveState: false });
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="Orders"
        description="All store orders (guest or logged-in checkout): COD, ZynlePay, PayPal. New COD orders are Pending until you update status."
      />

      <p class="text-sm text-zinc-600 dark:text-zinc-400">
        <span class="font-semibold text-zinc-900 dark:text-white">{{ totalAllInDb }}</span>
        order{{ totalAllInDb === 1 ? '' : 's' }} in the database
        <template v-if="rows.length && totalAllInDb">
          · showing {{ rows.length }} on this page
        </template>
        <template v-else-if="totalAllInDb === 0">
          . If you already took a test order, run
          <code class="rounded bg-zinc-200 px-1 text-xs dark:bg-zinc-700">php artisan migrate --force</code>
          on the server and confirm
          <code class="rounded bg-zinc-200 px-1 text-xs dark:bg-zinc-700">DB_*</code> in
          <code class="rounded bg-zinc-200 px-1 text-xs dark:bg-zinc-700">.env</code> matches the database checkout uses.
        </template>
      </p>

      <div
        v-if="statusFilterVal"
        class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100"
      >
        Showing only
        <strong>{{ statusOpts[statusFilterVal] || statusFilterVal }}</strong>
        orders.
        <button
          type="button"
          class="ml-2 font-semibold text-amber-800 underline hover:no-underline dark:text-amber-300"
          @click="setStatusFilter(null)"
        >
          Show all orders
        </button>
      </div>

      <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Filter:</span>
        <button
          type="button"
          :class="!statusFilterVal
            ? 'bg-amber-500/15 font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300'
            : 'bg-white text-zinc-700 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700'"
          class="rounded-xl border border-zinc-300 px-4 py-2 text-sm shadow-sm dark:border-zinc-600"
          @click="setStatusFilter(null)"
        >
          All
        </button>
        <button
          v-for="(label, statusKey) in statusOpts"
          :key="statusKey"
          type="button"
          :class="statusFilterVal === statusKey
            ? 'bg-amber-500/15 font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300'
            : 'bg-white text-zinc-700 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700'"
          class="rounded-xl border border-zinc-300 px-4 py-2 text-sm shadow-sm dark:border-zinc-600"
          @click="setStatusFilter(statusKey)"
        >
          {{ label }}
        </button>
      </div>

      <DataCard class="p-0">
        <div v-if="rows.length > 0">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ paginationBar.total }}</span>
              {{ paginationBar.total === 1 ? 'order' : 'orders' }} total (this page: {{ rows.length }})
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Date</th>
                  <th scope="col" class="admin-th">Order #</th>
                  <th scope="col" class="admin-th">Customer</th>
                  <th scope="col" class="admin-th">Status</th>
                  <th scope="col" class="admin-th">Total</th>
                  <th scope="col" class="admin-th admin-th-last text-right">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="order in rows" :key="order.id" class="admin-row">
                  <td class="admin-td admin-td-first whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="admin-td font-mono font-medium text-zinc-900 dark:text-white">{{ order.number ?? '—' }}</td>
                  <td class="admin-td">
                    <span class="font-medium text-zinc-900 dark:text-white">{{ order.guest_name ?? '—' }}</span>
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400">{{ order.guest_email ?? '—' }}</span>
                  </td>
                  <td class="admin-td">
                    <span :class="['status-badge', statusClass(order.status)]">{{ statusOpts[order.status] || order.status || '—' }}</span>
                  </td>
                  <td class="admin-td font-semibold text-zinc-900 dark:text-white">
                    {{ formatPrice(order.total ?? 0, order.currency ?? 'ZMW') }}
                  </td>
                  <td class="admin-td admin-td-last text-right">
                    <Link
                      :href="typeof route === 'function' ? route('admin.orders.show', order.id) : '#'"
                      class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
                    >
                      <Eye class="h-4 w-4" stroke-width="2" />
                      View
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <PaginationBar :data="paginationBar" />
        </div>
        <div v-else class="p-6">
          <EmptyState
            v-if="filterHidesOrders"
            :icon="ShoppingBag"
            title="No orders match this filter"
            :description="`There ${totalAllInDb === 1 ? 'is' : 'are'} ${totalAllInDb} order(s) in the database, but none with status '${statusOpts[statusFilterVal] || statusFilterVal}'. Clear the filter to see every order.`"
          >
            <template #actions>
              <button
                type="button"
                class="mt-4 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-zinc-900 hover:bg-amber-400"
                @click="setStatusFilter(null)"
              >
                Show all orders
              </button>
            </template>
          </EmptyState>
          <EmptyState
            v-else
            :icon="ShoppingBag"
            title="No orders on this page"
            description="If the line above shows orders in the database but this list is empty, hard-refresh (Ctrl+F5) or redeploy the latest frontend build (npm run build). The table reads prop adminOrderRows from the server."
          />
        </div>
      </DataCard>
    </div>
  </AdminLayout>
</template>
