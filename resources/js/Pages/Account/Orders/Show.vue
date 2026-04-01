<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AccountLayout from '@/Layouts/AccountLayout.vue';
import { ChevronLeft, Package, MapPin, CreditCard } from 'lucide-vue-next';

const props = defineProps({
  order: { type: Object, required: true },
  statusOptions: { type: Object, default: () => ({}) },
});

const order = props.order;
const address = order.shipping_address || {};

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
function formatCurrency(amount, currency = 'ZMW') {
  return new Intl.NumberFormat('en-ZM', { style: 'currency', currency, minimumFractionDigits: 2 }).format(amount);
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
function paymentLabel(method) {
  if (method === 'cod') return 'Cash on Delivery (COD)';
  if (method === 'zynlepay') return 'ZynlePay (Online)';
  if (method === 'paypal') return 'PayPal';
  return method;
}
</script>

<template>
  <AccountLayout>
    <Head :title="title || `Order ${order.number}`" />
    <div class="space-y-6">
      <div class="flex flex-wrap items-center gap-4">
        <Link
          :href="route('account.orders.index')"
          class="inline-flex items-center gap-1.5 text-sm font-medium text-editorial-slate hover:text-editorial-coral dark:text-zinc-400"
        >
          <ChevronLeft class="h-4 w-4" />
          Back to orders
        </Link>
      </div>

      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h1 class="font-editorial text-2xl font-bold tracking-tight text-editorial-ink dark:text-white">
            Order {{ order.number }}
          </h1>
          <p class="mt-1 text-sm text-editorial-slate dark:text-zinc-400">Placed on {{ formatDate(order.created_at) }}</p>
        </div>
        <span
          :class="['inline-flex rounded-full px-4 py-2 text-sm font-semibold', statusClass(order.status)]"
        >
          {{ statusLabel(order.status) }}
        </span>
      </div>

      <!-- Order status explanation -->
      <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
        <h2 class="flex items-center gap-2 font-semibold text-editorial-ink dark:text-white">
          <Package class="h-5 w-5 text-editorial-coral" stroke-width="2" />
          Order status
        </h2>
        <p class="mt-2 text-sm text-editorial-slate dark:text-zinc-400">
          <template v-if="order.status === 'pending'">Your order has been received. We will confirm and start processing soon.</template>
          <template v-else-if="order.status === 'confirmed'">Your order is confirmed. We are preparing your items.</template>
          <template v-else-if="order.status === 'processing'">Your order is being processed and will be prepared for delivery.</template>
          <template v-else-if="order.status === 'shipped'">Your order has been shipped. It is on its way to you.</template>
          <template v-else-if="order.status === 'delivered'">Your order has been delivered. Thank you for shopping with us.</template>
          <template v-else-if="order.status === 'cancelled'">This order was cancelled.</template>
          <template v-else>Current status: {{ statusLabel(order.status) }}.</template>
        </p>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <!-- Items -->
        <div class="lg:col-span-2 rounded-xl border-2 border-editorial-ink/10 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/50">
          <div class="border-b border-editorial-ink/10 px-5 py-4 dark:border-zinc-600">
            <h2 class="font-semibold text-editorial-ink dark:text-white">Items ({{ order.items?.length ?? 0 }})</h2>
          </div>
          <ul class="divide-y divide-editorial-ink/10 dark:divide-zinc-600">
            <li
              v-for="item in order.items"
              :key="item.id"
              class="flex flex-wrap items-center gap-4 px-5 py-4"
            >
              <div class="min-w-0 flex-1">
                <Link
                  v-if="item.product_slug"
                  :href="route('products.show', item.product_slug)"
                  class="font-medium text-editorial-ink hover:text-editorial-coral dark:text-white dark:hover:text-editorial-coral"
                >
                  {{ item.product_name }}
                </Link>
                <span v-else class="font-medium text-editorial-ink dark:text-white">{{ item.product_name }}</span>
                <p class="text-sm text-editorial-slate dark:text-zinc-400">Qty: {{ item.quantity }} × {{ formatCurrency(item.price, order.currency) }}</p>
              </div>
              <p class="font-semibold text-editorial-ink dark:text-white">
                {{ formatCurrency(item.line_total, order.currency) }}
              </p>
            </li>
          </ul>
          <div class="border-t border-editorial-ink/10 px-5 py-4 dark:border-zinc-600">
            <div class="flex justify-between text-sm">
              <span class="text-editorial-slate dark:text-zinc-400">Subtotal</span>
              <span class="font-medium text-editorial-ink dark:text-white">{{ formatCurrency(order.subtotal, order.currency) }}</span>
            </div>
            <div class="mt-2 flex justify-between text-base font-semibold">
              <span>Total</span>
              <span class="text-editorial-coral">{{ formatCurrency(order.total, order.currency) }}</span>
            </div>
          </div>
        </div>

        <!-- Shipping & payment -->
        <div class="space-y-6">
          <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
            <h2 class="flex items-center gap-2 font-semibold text-editorial-ink dark:text-white">
              <MapPin class="h-5 w-5 text-editorial-coral" stroke-width="2" />
              Delivery address
            </h2>
            <p class="mt-2 text-sm text-editorial-slate dark:text-zinc-400 whitespace-pre-line">
              {{ address.name || '' }}
              {{ address.address_line_1 || '' }}
              {{ address.address_line_2 ? address.address_line_2 + '\n' : '' }}
              {{ address.city || '' }}{{ address.region ? ', ' + address.region : '' }}
              {{ address.postal_code || '' }}
              {{ address.country || '' }}
              {{ address.phone ? '\n' + address.phone : '' }}
            </p>
          </div>
          <div class="rounded-xl border-2 border-editorial-ink/10 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
            <h2 class="flex items-center gap-2 font-semibold text-editorial-ink dark:text-white">
              <CreditCard class="h-5 w-5 text-editorial-coral" stroke-width="2" />
              Payment
            </h2>
            <p class="mt-2 text-sm text-editorial-slate dark:text-zinc-400">{{ paymentLabel(order.payment_method) }}</p>
          </div>
          <div v-if="order.order_notes" class="rounded-xl border-2 border-editorial-ink/10 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
            <h2 class="font-semibold text-editorial-ink dark:text-white">Order notes</h2>
            <p class="mt-2 text-sm text-editorial-slate dark:text-zinc-400 whitespace-pre-wrap">{{ order.order_notes }}</p>
          </div>
        </div>
      </div>
    </div>
  </AccountLayout>
</template>
