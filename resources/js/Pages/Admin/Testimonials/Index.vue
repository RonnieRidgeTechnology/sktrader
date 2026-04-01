<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { MessageSquareQuote, Plus, Pencil, Trash2, Check, X } from 'lucide-vue-next';

const props = defineProps({ testimonials: Object });
const list = props.testimonials?.data || [];
const total = props.testimonials?.total ?? list.length;

function destroy(id) {
  if (confirm('Delete this testimonial?')) {
    router.delete(route('admin.testimonials.destroy', id));
  }
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="Testimonials"
        description="Manage testimonials shown on the homepage."
      >
        <template #actions>
          <Link
            :href="route('admin.testimonials.create')"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            <Plus class="h-4 w-4" stroke-width="2" />
            Add testimonial
          </Link>
        </template>
      </PageHeader>

      <DataCard class="p-0">
        <template v-if="list.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ total }}</span>
              {{ total === 1 ? 'testimonial' : 'testimonials' }}
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Author</th>
                  <th scope="col" class="admin-th">Role / Company</th>
                  <th scope="col" class="admin-th">Quote</th>
                  <th scope="col" class="admin-th">Status</th>
                  <th scope="col" class="admin-th admin-th-last text-right"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="t in list" :key="t.id" class="admin-row group transition-colors">
                  <td class="admin-td admin-td-first">
                    <div class="flex items-center gap-4">
                      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 transition group-hover:bg-amber-100 group-hover:text-amber-700 dark:bg-zinc-700 dark:text-zinc-300 dark:group-hover:bg-amber-900/30 dark:group-hover:text-amber-400" aria-hidden="true">
                        <MessageSquareQuote class="h-5 w-5" stroke-width="2" />
                      </div>
                      <span class="font-semibold text-zinc-900 dark:text-white">{{ t.name }}</span>
                    </div>
                  </td>
                  <td class="admin-td text-zinc-600 dark:text-zinc-400">{{ [t.role, t.company].filter(Boolean).join(' · ') || '—' }}</td>
                  <td class="admin-td max-w-xs truncate text-zinc-600 dark:text-zinc-400">{{ t.quote }}</td>
                  <td class="admin-td">
                    <span :class="t.status ? 'status-badge status-badge-active' : 'status-badge status-badge-inactive'">
                      <component :is="t.status ? Check : X" class="h-3.5 w-3.5 shrink-0" stroke-width="2.5" />
                      {{ t.status ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="admin-td admin-td-last text-right">
                    <div class="flex items-center justify-end gap-1">
                      <Link :href="route('admin.testimonials.edit', t.id)" class="admin-action admin-action-edit" title="Edit">
                        <Pencil class="h-4 w-4" stroke-width="2" /><span class="hidden sm:inline">Edit</span>
                      </Link>
                      <span class="admin-action-divider" aria-hidden="true" />
                      <button type="button" class="admin-action admin-action-delete" title="Delete" @click="destroy(t.id)">
                        <Trash2 class="h-4 w-4" stroke-width="2" /><span class="hidden sm:inline">Delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
        <EmptyState v-else :icon="MessageSquareQuote" title="No testimonials yet" description="Add testimonials to display in the homepage carousel.">
          <template #actions>
            <Link :href="route('admin.testimonials.create')" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">
              <Plus class="h-4 w-4" stroke-width="2" /> Add testimonial
            </Link>
          </template>
        </EmptyState>
        <PaginationBar :data="testimonials" />
      </DataCard>
    </div>
  </AdminLayout>
</template>
