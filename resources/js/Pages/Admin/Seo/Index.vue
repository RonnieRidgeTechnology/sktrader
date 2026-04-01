<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import { Search, Pencil } from 'lucide-vue-next';

defineProps({ settings: Array });
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="SEO Settings"
        description="Manage meta title and description per page for better search visibility."
      />

      <DataCard class="p-0">
        <template v-if="settings && settings.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ settings.length }}</span>
              {{ settings.length === 1 ? 'page' : 'pages' }}
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Page</th>
                  <th scope="col" class="admin-th">Meta title</th>
                  <th scope="col" class="admin-th admin-th-last text-right"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="s in settings" :key="s.id" class="admin-row group transition-colors">
                  <td class="admin-td admin-td-first">
                    <div class="flex items-center gap-4">
                      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 transition group-hover:bg-amber-100 group-hover:text-amber-700 dark:bg-zinc-700 dark:text-zinc-300 dark:group-hover:bg-amber-900/30 dark:group-hover:text-amber-400" aria-hidden="true">
                        <Search class="h-5 w-5" stroke-width="2" />
                      </div>
                      <span class="font-semibold text-zinc-900 dark:text-white">{{ s.page_key }}</span>
                    </div>
                  </td>
                  <td class="admin-td max-w-md truncate text-zinc-600 dark:text-zinc-400">{{ s.meta_title || '—' }}</td>
                  <td class="admin-td admin-td-last text-right">
                    <Link
                      :href="route('admin.seo.edit', s.id)"
                      class="admin-action admin-action-edit"
                      title="Edit"
                    >
                      <Pencil class="h-4 w-4" stroke-width="2" />
                      <span class="hidden sm:inline">Edit</span>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
        <div v-else class="px-6 py-12 text-center">
          <Search class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" stroke-width="1.5" />
          <p class="mt-3 text-sm font-medium text-zinc-900 dark:text-white">No SEO settings</p>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">SEO records are managed by the system.</p>
        </div>
      </DataCard>
    </div>
  </AdminLayout>
</template>
