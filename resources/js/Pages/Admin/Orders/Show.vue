<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import { Link } from '@inertiajs/vue3';
import { ShoppingBag, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
  order: { type: Object, required: true },
  statusOptions: { type: Object, default: () => ({}) },
});

const page = usePage();

function formatPrice(value, currency) {
  const cur = currency || page.props.zuaaz?.store_currency || 'USD';
  return new Intl.NumberFormat(localeForIsoCurrency(cur), { style: 'currency', currency: cur, minimumFractionDigits: 2 }).format(value);
}

function formatDate(value) {
  if (!value) return '—';
  return new Date(value).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
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

function paymentMethodLabel(method) {
  if (method === 'cod') return 'Cash on Delivery (COD)';
  if (method === 'bank_transfer') return 'Bank Transfer';
  if (method === 'jazzcash') return 'JazzCash';
  return method || '—';
}

const shipping = props.order.shipping_address || {};
const addressLines = [
  shipping.address_line_1,
  shipping.address_line_2,
  [shipping.city, shipping.region].filter(Boolean).join(', '),
  shipping.postal_code,
  shipping.country,
].filter(Boolean);

const statusForm = useForm({ status: props.order.status });
function submitStatus() {
  statusForm.patch(route('admin.orders.update-status', props.order.id));
}

const trackingForm = useForm({
  courier_name: props.order.courier_name || '',
  tracking_number: props.order.tracking_number || '',
  tracking_url: props.order.tracking_url || '',
  notify_customer: true,
});
function submitTracking() {
  trackingForm.patch(route('admin.orders.update-tracking', props.order.id));
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        :title="`Order ${order.number}`"
        description="Order details and status. Update status to keep the customer informed."
      >
        <template #actions>
          <Link
            :href="route('admin.orders.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            <ArrowLeft class="h-4 w-4" stroke-width="2" />
            Back to orders
          </Link>
        </template>
      </PageHeader>

      <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
          <DataCard class="p-0">
            <div class="border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
              <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                  <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                    <ShoppingBag class="h-6 w-6" stroke-width="2" />
                  </div>
                  <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">{{ order.guest_name }}</p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ order.guest_email }}</p>
                  </div>
                </div>
                <span :class="['status-badge', statusClass(order.status)]">{{ statusOptions[order.status] || order.status }}</span>
              </div>
            </div>
            <dl class="divide-y divide-zinc-200 dark:divide-zinc-700">
              <div class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Order number</dt>
                <dd class="font-mono font-semibold text-zinc-900 dark:text-white">{{ order.number }}</dd>
              </div>
              <div class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Date</dt>
                <dd class="text-zinc-900 dark:text-white">{{ formatDate(order.created_at) }}</dd>
              </div>
              <div class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Payment</dt>
                <dd class="text-zinc-900 dark:text-white">{{ paymentMethodLabel(order.payment_method) }}</dd>
              </div>
              <div v-if="order.guest_phone" class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Phone</dt>
                <dd class="text-zinc-900 dark:text-white">{{ order.guest_phone }}</dd>
              </div>
              <div v-if="addressLines.length" class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Delivery address</dt>
                <dd class="text-zinc-900 dark:text-white whitespace-pre-line">{{ addressLines.join('\n') }}</dd>
              </div>
              <div v-if="order.order_notes" class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Order notes</dt>
                <dd class="whitespace-pre-wrap text-zinc-900 dark:text-white">{{ order.order_notes }}</dd>
              </div>
              <div v-if="order.payment_proof" class="flex flex-col gap-1 px-6 py-4 sm:flex-row sm:gap-4">
                <dt class="min-w-0 shrink-0 text-sm font-medium text-zinc-500 dark:text-zinc-400 sm:w-36">Payment Proof</dt>
                <dd>
                     <a :href="`/storage/${order.payment_proof}`" target="_blank" class="text-amber-600 hover:text-amber-700 dark:text-amber-400 hover:underline">View File / Receipt</a>
                </dd>
              </div>
            </dl>
          </DataCard>

          <DataCard class="p-0">
            <div class="border-b border-zinc-200 bg-zinc-50/80 px-6 py-3 dark:border-zinc-700 dark:bg-zinc-800/40">
              <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Items</h2>
            </div>
            <div class="overflow-x-auto">
              <table class="admin-table min-w-full">
                <thead>
                  <tr>
                    <th scope="col" class="admin-th admin-th-first">Product</th>
                    <th scope="col" class="admin-th text-right">Price</th>
                    <th scope="col" class="admin-th text-right">Qty</th>
                    <th scope="col" class="admin-th admin-th-last text-right">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                  <tr v-for="item in order.items" :key="item.id" class="admin-row">
                    <td class="admin-td admin-td-first font-medium text-zinc-900 dark:text-white">
                      <Link
                        v-if="item.product_slug"
                        :href="route('products.show', item.product_slug)"
                        target="_blank"
                        class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
                      >
                        {{ item.product_name }}
                      </Link>
                      <span v-else>{{ item.product_name }}</span>
                    </td>
                    <td class="admin-td text-right text-zinc-600 dark:text-zinc-400">{{ formatPrice(item.price, order.currency) }}</td>
                    <td class="admin-td text-right text-zinc-900 dark:text-white">{{ item.quantity }}</td>
                    <td class="admin-td admin-td-last text-right font-semibold text-zinc-900 dark:text-white">{{ formatPrice(item.line_total, order.currency) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="flex flex-col items-end gap-1 border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
              <div class="flex w-full max-w-xs justify-between text-sm">
                <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                <span class="font-medium text-zinc-900 dark:text-white">{{ formatPrice(order.subtotal, order.currency) }}</span>
              </div>
              <div class="flex w-full max-w-xs justify-between text-lg">
                <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                <span class="font-bold text-zinc-900 dark:text-white">{{ formatPrice(order.total, order.currency) }}</span>
              </div>
            </div>
          </DataCard>
        </div>

        <div class="lg:col-span-1">
          <FormCard title="Update status" description="Change order status. Use this to track progress and communicate with the customer.">
            <form @submit.prevent="submitStatus" class="space-y-4">
              <div>
                <label for="status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</label>
                <select
                  id="status"
                  v-model="statusForm.status"
                  class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                >
                  <option v-for="(label, value) in statusOptions" :key="value" :value="value">{{ label }}</option>
                </select>
              </div>
              <button
                type="submit"
                :disabled="statusForm.processing || statusForm.status === order.status"
                class="w-full rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
              >
                {{ statusForm.processing ? 'Saving…' : 'Update status' }}
              </button>
            </form>
          </FormCard>
          
          <div class="mt-6"></div>

          <FormCard title="Tracking details" description="Add or update courier and tracking information.">
            <form @submit.prevent="submitTracking" class="space-y-4">
              <div>
                <label for="courier_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Courier Name</label>
                <input
                  id="courier_name"
                  v-model="trackingForm.courier_name"
                  type="text"
                  placeholder="e.g. TCS, Leopard, DHL"
                  class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                />
              </div>
              <div>
                <label for="tracking_number" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tracking Number</label>
                <input
                  id="tracking_number"
                  v-model="trackingForm.tracking_number"
                  type="text"
                  class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                />
              </div>
              <div>
                <label for="tracking_url" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tracking URL <span class="text-zinc-400 font-normal">(optional)</span></label>
                <input
                  id="tracking_url"
                  v-model="trackingForm.tracking_url"
                  type="url"
                  placeholder="https://"
                  class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                />
              </div>
              <div class="flex items-center gap-2 mt-4">
                   <input type="checkbox" id="notify_customer" v-model="trackingForm.notify_customer" class="rounded border-zinc-300 text-amber-600 focus:ring-amber-500" />
                   <label for="notify_customer" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Notify customer via email</label>
              </div>
              <button
                type="submit"
                :disabled="trackingForm.processing"
                class="w-full mt-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
              >
                {{ trackingForm.processing ? 'Saving…' : 'Update Tracking' }}
              </button>
            </form>
          </FormCard>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
