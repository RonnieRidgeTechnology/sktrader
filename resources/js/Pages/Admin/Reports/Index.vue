<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import { BarChart3, TrendingUp, DollarSign, ShoppingBag, PieChart } from 'lucide-vue-next';
import { localeForIsoCurrency } from '@/composables/useStoreCurrency';

const props = defineProps({
  year: Number,
  monthlyData: Array,
  stats: Object,
  paymentBreakdown: Array,
});

const page = usePage();
const currency = computed(() => page.props.currency || 'USD');

function formatPrice(value) {
  return new Intl.NumberFormat(localeForIsoCurrency(currency.value), {
    style: 'currency',
    currency: currency.value,
    minimumFractionDigits: 0,
  }).format(value);
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="Sales Reports"
        description="View your yearly, monthly sales data, and payment statistics."
      >
        <template #actions>
            <div class="flex items-center gap-2">
                 <Link :href="route('admin.reports.index', { year: year - 1 })" class="px-3 py-1.5 border border-zinc-200 dark:border-zinc-700 rounded-lg shrink-0 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                      Prev Year
                 </Link>
                 <span class="font-semibold px-2 dark:text-white">{{ year }}</span>
                 <Link :href="route('admin.reports.index', { year: year + 1 })" class="px-3 py-1.5 border border-zinc-200 dark:border-zinc-700 rounded-lg shrink-0 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                      Next Year
                 </Link>
            </div>
        </template>
      </PageHeader>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <DataCard>
          <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
              <DollarSign class="h-6 w-6" stroke-width="2" />
            </div>
            <div>
              <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Yearly Revenue</p>
              <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ formatPrice(stats.yearlySales) }}</p>
            </div>
          </div>
        </DataCard>

        <DataCard>
          <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
              <ShoppingBag class="h-6 w-6" stroke-width="2" />
            </div>
            <div>
              <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Yearly Orders</p>
              <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ stats.yearlyOrders }}</p>
            </div>
          </div>
        </DataCard>

        <DataCard>
          <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
              <TrendingUp class="h-6 w-6" stroke-width="2" />
            </div>
            <div>
              <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">YoY Growth</p>
              <p class="text-2xl font-bold text-zinc-900 dark:text-white" :class="stats.growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500'">
                   {{ stats.growth > 0 ? '+' : '' }}{{ stats.growth }}%
              </p>
            </div>
          </div>
        </DataCard>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
           <div class="lg:col-span-2 space-y-6">
                <!-- Monthly Breakdown -->
                <DataCard title="Monthly Breakdown" class="p-0">
                  <div class="overflow-x-auto">
                    <table class="admin-table min-w-full">
                      <thead>
                        <tr>
                          <th class="admin-th admin-th-first">Month</th>
                          <th class="admin-th text-right">Orders</th>
                          <th class="admin-th admin-th-last text-right">Revenue</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                        <tr v-for="data in monthlyData" :key="data.month" class="admin-row">
                          <td class="admin-td admin-td-first font-medium text-zinc-900 dark:text-white">{{ data.month }}</td>
                          <td class="admin-td text-right text-zinc-600 dark:text-zinc-400">{{ data.orders }}</td>
                          <td class="admin-td admin-td-last text-right font-semibold text-zinc-900 dark:text-white">{{ formatPrice(data.sales) }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </DataCard>
           </div>
           
           <div class="lg:col-span-1 space-y-6">
                <!-- Payment methods -->
                <DataCard title="Payment Method Usage">
                     <div v-if="paymentBreakdown.length" class="space-y-4">
                          <div v-for="pm in paymentBreakdown" :key="pm.method" class="flex justify-between items-center bg-zinc-50 dark:bg-zinc-800/40 p-4 border border-zinc-200 dark:border-zinc-700/50 rounded-xl">
                               <div class="flex items-center gap-3">
                                    <PieChart class="w-5 h-5 text-zinc-400" />
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200 uppercase">{{ pm.method.replace('_', ' ') }}</span>
                               </div>
                               <span class="bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full text-sm font-bold">{{ pm.total }}</span>
                          </div>
                     </div>
                     <div v-else class="text-center py-6 text-zinc-500">
                          No payment data available.
                     </div>
                </DataCard>
           </div>
      </div>
    </div>
  </AdminLayout>
</template>
