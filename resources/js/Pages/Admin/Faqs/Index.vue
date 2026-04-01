<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { HelpCircle, Plus, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps({ faqs: Object });
const faqs = props.faqs || { data: [] };

function destroy(id) {
  if (confirm('Delete this FAQ?')) {
    router.delete(route('admin.faqs.destroy', id));
  }
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader title="FAQs" description="Frequently asked questions for the website.">
        <template #actions>
          <Link :href="route('admin.faqs.create')" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
            <Plus class="h-4 w-4" stroke-width="2" /> Add FAQ
          </Link>
        </template>
      </PageHeader>
      <DataCard class="p-0">
        <template v-if="faqs.data && faqs.data.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ faqs.total ?? faqs.data.length }}</span>
              {{ (faqs.total ?? faqs.data.length) === 1 ? 'FAQ' : 'FAQs' }}
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Question</th>
                  <th scope="col" class="admin-th">Order</th>
                  <th scope="col" class="admin-th">Status</th>
                  <th scope="col" class="admin-th admin-th-last text-right"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="faq in faqs.data" :key="faq.id" class="admin-row group transition-colors">
                  <td class="admin-td admin-td-first font-medium text-zinc-900 dark:text-white">{{ faq.question }}</td>
                  <td class="admin-td whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ faq.sort_order }}</td>
                  <td class="admin-td">
                    <span :class="faq.status ? 'status-badge status-badge-active' : 'status-badge status-badge-inactive'">{{ faq.status ? 'Active' : 'Inactive' }}</span>
                  </td>
                  <td class="admin-td admin-td-last text-right">
                    <div class="flex items-center justify-end gap-1">
                      <Link :href="route('admin.faqs.edit', faq.id)" class="admin-action admin-action-edit" title="Edit"><Pencil class="h-4 w-4" stroke-width="2" /></Link>
                      <span class="admin-action-divider" aria-hidden="true" />
                      <button type="button" class="admin-action admin-action-delete" title="Delete" @click="destroy(faq.id)"><Trash2 class="h-4 w-4" stroke-width="2" /></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <PaginationBar :data="faqs" />
        </template>
        <EmptyState v-else :icon="HelpCircle" title="No FAQs yet" description="Add FAQs to help visitors find answers.">
          <template #actions>
            <Link :href="route('admin.faqs.create')" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"><Plus class="h-4 w-4" stroke-width="2" /> Add FAQ</Link>
          </template>
        </EmptyState>
      </DataCard>
    </div>
  </AdminLayout>
</template>
