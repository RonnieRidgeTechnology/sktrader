<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import { Layout, Pencil, Eye, EyeOff } from 'lucide-vue-next';

defineProps({ sections: Array });

/** Display names match the public home page sections. */
function sectionLabel(key) {
  const labels = {
    hero: 'Room hero & Bento',
    spotlight: 'Spotlight',
    journey_strip: 'Journey strip',
    why_pillars: 'Why us',
    reels_strip: 'Video reels',
    quote_block: 'Testimonials',
    delivery_line: 'Delivery line',
    final_cta: 'Final CTA',
  };
  return labels[key] || (key || '').replace(/_/g, ' ');
}

</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        title="Home Page"
        description="Edit sections. Click Edit to change text and images."
      />

      <DataCard class="p-0">
        <template v-if="sections && sections.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ sections.length }}</span>
              {{ sections.length === 1 ? 'section' : 'sections' }}
            </p>
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Section</th>
                  <th scope="col" class="admin-th">Title</th>
                  <th scope="col" class="admin-th">Visibility</th>
                  <th scope="col" class="admin-th">Order</th>
                  <th scope="col" class="admin-th admin-th-last text-right"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="s in sections" :key="s.id" class="admin-row group transition-colors">
                  <td class="admin-td admin-td-first">
                    <div class="flex items-center gap-4">
                      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 transition group-hover:bg-amber-100 group-hover:text-amber-700 dark:bg-zinc-700 dark:text-zinc-300 dark:group-hover:bg-amber-900/30 dark:group-hover:text-amber-400" aria-hidden="true">
                        <Layout class="h-5 w-5" stroke-width="2" />
                      </div>
                      <div>
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ sectionLabel(s.key) }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="admin-td max-w-xs truncate text-zinc-600 dark:text-zinc-400">{{ s.title || '—' }}</td>
                  <td class="admin-td">
                    <span
                      :class="[
                        'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
                        s.visible
                          ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                          : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400',
                      ]"
                    >
                      <Eye v-if="s.visible" class="h-3.5 w-3.5" stroke-width="2" />
                      <EyeOff v-else class="h-3.5 w-3.5" stroke-width="2" />
                      {{ s.visible ? 'Visible' : 'Hidden' }}
                    </span>
                  </td>
                  <td class="admin-td text-zinc-500 dark:text-zinc-400">{{ s.sort_order }}</td>
                  <td class="admin-td admin-td-last text-right">
                    <Link
                      :href="route('admin.homepage.edit', s.key)"
                      class="admin-action admin-action-edit"
                      title="Edit section"
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
          <Layout class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" stroke-width="1.5" />
          <p class="mt-3 text-sm font-medium text-zinc-900 dark:text-white">No sections</p>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Homepage sections are created by the system.</p>
        </div>
      </DataCard>
    </div>
  </AdminLayout>
</template>
