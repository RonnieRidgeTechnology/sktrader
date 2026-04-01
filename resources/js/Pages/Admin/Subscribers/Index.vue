<script setup>
import { ref, computed, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { Mail, Download, Search, ArrowUpDown, ArrowUp, ArrowDown } from 'lucide-vue-next';

const props = defineProps({
  subscribers: { type: Object, default: () => ({}) },
  filters: { type: Object, default: () => ({}) },
});

const subscribers = computed(() => props.subscribers || { data: [] });
const filters = computed(() => props.filters || {});

const searchInput = ref(filters.value.search ?? '');

watch(() => props.filters?.search, (v) => {
  searchInput.value = v ?? '';
});

function buildQuery(overrides = {}) {
  const q = { ...filters.value, ...overrides };
  const out = {};
  if (q.search) out.search = q.search;
  if (q.sort) out.sort = q.sort;
  if (q.dir) out.dir = q.dir;
  return out;
}

function applySearch() {
  router.get(route('admin.subscribers.index'), { ...buildQuery(), search: searchInput.value || undefined }, { preserveState: true });
}

function sortBy(column) {
  const currentSort = filters.value.sort;
  const currentDir = filters.value.dir;
  const nextDir = currentSort === column && currentDir === 'asc' ? 'desc' : 'asc';
  router.get(route('admin.subscribers.index'), { ...buildQuery(), sort: column, dir: nextDir }, { preserveState: true });
}

function exportUrl() {
  const q = buildQuery();
  const params = new URLSearchParams(q);
  const base = route('admin.subscribers.export.csv');
  return params.toString() ? `${base}?${params.toString()}` : base;
}

function sortIcon(column) {
  if (filters.value.sort !== column) return ArrowUpDown;
  return filters.value.dir === 'asc' ? ArrowUp : ArrowDown;
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader title="Newsletter subscribers" description="People who subscribed via the footer form.">
        <template #actions>
          <a
            :href="exportUrl()"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            <Download class="h-4 w-4" stroke-width="2" />
            Export CSV
          </a>
        </template>
      </PageHeader>

      <DataCard class="p-0">
        <!-- Toolbar: search + count -->
        <div class="flex flex-col gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40 sm:flex-row sm:items-center sm:justify-between">
          <p class="text-sm text-zinc-600 dark:text-zinc-400">
            <span class="font-semibold text-zinc-900 dark:text-white">{{ subscribers.total ?? subscribers.data.length }}</span>
            {{ (subscribers.total ?? subscribers.data.length) === 1 ? 'subscriber' : 'subscribers' }}
          </p>
          <form class="flex min-w-0 gap-2 sm:w-80" @submit.prevent="applySearch">
            <div class="relative min-w-0 flex-1">
              <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" stroke-width="2" aria-hidden="true" />
              <input
                v-model="searchInput"
                type="search"
                placeholder="Search by email..."
                class="block w-full rounded-lg border border-zinc-300 bg-white py-2.5 pl-9 pr-4 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder:text-zinc-500"
              />
            </div>
            <button
              type="submit"
              class="shrink-0 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
            >
              Search
            </button>
          </form>
        </div>

        <template v-if="subscribers.data && subscribers.data.length">
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first w-20">
                    <button
                      type="button"
                      class="inline-flex items-center gap-1.5 font-semibold transition hover:text-amber-600 dark:hover:text-amber-400"
                      @click="sortBy('id')"
                    >
                      #
                      <component :is="sortIcon('id')" class="h-4 w-4" stroke-width="2" />
                    </button>
                  </th>
                  <th scope="col" class="admin-th">
                    <button
                      type="button"
                      class="inline-flex items-center gap-1.5 font-semibold transition hover:text-amber-600 dark:hover:text-amber-400"
                      @click="sortBy('email')"
                    >
                      Email
                      <component :is="sortIcon('email')" class="h-4 w-4" stroke-width="2" />
                    </button>
                  </th>
                  <th scope="col" class="admin-th admin-th-last">
                    <button
                      type="button"
                      class="inline-flex items-center gap-1.5 font-semibold transition hover:text-amber-600 dark:hover:text-amber-400"
                      @click="sortBy('subscribed_at')"
                    >
                      Subscribed at
                      <component :is="sortIcon('subscribed_at')" class="h-4 w-4" stroke-width="2" />
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="sub in subscribers.data" :key="sub.id" class="admin-row">
                  <td class="admin-td admin-td-first whitespace-nowrap text-zinc-500 dark:text-zinc-400">{{ sub.id }}</td>
                  <td class="admin-td">
                    <a :href="`mailto:${sub.email}`" class="font-medium text-amber-600 underline-offset-2 hover:underline dark:text-amber-400">{{ sub.email }}</a>
                  </td>
                  <td class="admin-td admin-td-last whitespace-nowrap text-zinc-600 dark:text-zinc-400">
                    {{ sub.subscribed_at ? new Date(sub.subscribed_at).toLocaleString() : '—' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <PaginationBar :data="subscribers" />
        </template>

        <template v-else>
          <div class="p-6">
            <EmptyState
              :icon="Mail"
              title="No subscribers yet"
              description="Newsletter signups from the footer will appear here."
            />
          </div>
        </template>
      </DataCard>
    </div>
  </AdminLayout>
</template>
