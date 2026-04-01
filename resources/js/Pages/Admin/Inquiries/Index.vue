<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { Link } from '@inertiajs/vue3';
import { Inbox, Download, Eye } from 'lucide-vue-next';

const props = defineProps({ inquiries: Object });
const inquiries = props.inquiries || { data: [] };
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader title="Inquiries" description="Quote requests and contact form submissions.">
        <template #actions>
          <a :href="route('admin.inquiries.export.csv')" class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
            <Download class="h-4 w-4" stroke-width="2" /> Export CSV
          </a>
        </template>
      </PageHeader>
      <DataCard class="p-0">
        <template v-if="inquiries.data && inquiries.data.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ inquiries.total ?? inquiries.data.length }}</span>
              {{ (inquiries.total ?? inquiries.data.length) === 1 ? 'inquiry' : 'inquiries' }}
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Date</th>
                  <th scope="col" class="admin-th">Name</th>
                  <th scope="col" class="admin-th">Email</th>
                  <th scope="col" class="admin-th">Product interest</th>
                  <th scope="col" class="admin-th admin-th-last text-right">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="inquiry in inquiries.data" :key="inquiry.id" class="admin-row">
                  <td class="admin-td admin-td-first whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ new Date(inquiry.created_at).toLocaleDateString() }}</td>
                  <td class="admin-td font-medium text-zinc-900 dark:text-white">{{ inquiry.name }}</td>
                  <td class="admin-td text-zinc-600 dark:text-zinc-400">{{ inquiry.email }}</td>
                  <td class="admin-td text-zinc-600 dark:text-zinc-400">{{ inquiry.product_interest || '—' }}</td>
                  <td class="admin-td admin-td-last text-right">
                    <Link
                      :href="route('admin.inquiries.show', inquiry.id)"
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
          <PaginationBar :data="inquiries" />
        </template>
        <template v-else>
          <div class="p-6">
            <EmptyState :icon="Inbox" title="No inquiries yet" description="Inquiries from the contact form will appear here." />
          </div>
        </template>
      </DataCard>
    </div>
  </AdminLayout>
</template>
