<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import PaginationBar from '@/Components/Admin/PaginationBar.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { storageUrl } from '@/utils/storageUrl';
import { Package, Plus, Pencil, Trash2, Check, X } from 'lucide-vue-next';

const props = defineProps({ products: Object });
const list = computed(() => props.products?.data ?? []);
const total = computed(() => props.products?.total ?? list.value.length);

function destroy(id, name) {
  if (confirm('Delete product "' + name + '"?')) {
    router.delete(route('admin.products.destroy', id));
  }
}

const togglingId = ref(null);
const featuredOverrides = ref({});
const toggleError = ref(null);
function isFeatured(p) {
  if (featuredOverrides.value[p.id] !== undefined) return featuredOverrides.value[p.id];
  return !!p.is_featured;
}
function toggleFeatured(p) {
  if (togglingId.value !== null) return;
  toggleError.value = null;
  const next = !isFeatured(p);
  featuredOverrides.value[p.id] = next;
  togglingId.value = p.id;
  const url = route('admin.products.featured', p.id);
  router.patch(url, {}, {
    preserveScroll: true,
    onFinish: () => { togglingId.value = null; },
    onSuccess: () => {
      const o = { ...featuredOverrides.value };
      delete o[p.id];
      featuredOverrides.value = o;
    },
    onError: (errors) => {
      featuredOverrides.value = { ...featuredOverrides.value, [p.id]: !next };
      const msg = typeof errors === 'string' ? errors : (errors?.message || (typeof errors === 'object' && errors && Object.values(errors).flat().find(Boolean)) || 'Could not update featured status.');
      toggleError.value = msg;
    },
  });
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader title="Products" description="Manage your product catalog.">
        <template #actions>
          <Link
            :href="route('admin.products.create')"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            <Plus class="h-4 w-4" stroke-width="2" />
            Add product
          </Link>
        </template>
      </PageHeader>

      <DataCard class="p-0">
        <template v-if="list.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ total }}</span>
              {{ total === 1 ? 'product' : 'products' }}
            </p>
          </div>
          <div v-if="toggleError" class="mx-6 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
            {{ toggleError }}
          </div>
          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first">Product</th>
                  <th scope="col" class="admin-th">Category</th>
                  <th scope="col" class="admin-th">Status</th>
                  <th scope="col" class="admin-th">Feature Product</th>
                  <th scope="col" class="admin-th admin-th-last text-right"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr v-for="p in list" :key="p.id" class="admin-row group transition-colors">
                  <td class="admin-td admin-td-first">
                    <div class="flex items-center gap-4">
                      <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-700"
                        aria-hidden="true"
                      >
                        <img
                          v-if="p.image_url || p.image"
                          :src="p.image_url || storageUrl(p.image)"
                          :alt="p.name"
                          class="h-full w-full object-cover"
                        />
                        <Package v-else class="h-5 w-5 text-zinc-500 dark:text-zinc-400" stroke-width="2" />
                      </div>
                      <span class="font-semibold text-zinc-900 dark:text-white">{{ p.name }}</span>
                    </div>
                  </td>
                  <td class="admin-td text-zinc-600 dark:text-zinc-400">{{ p.category ? (p.category.parent ? `${p.category.parent.name} › ${p.category.name}` : p.category.name) : '—' }}</td>
                  <td class="admin-td">
                    <span :class="p.status ? 'status-badge status-badge-active' : 'status-badge status-badge-inactive'">
                      <component :is="p.status ? Check : X" class="h-3.5 w-3.5 shrink-0" stroke-width="2.5" />
                      {{ p.status ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="admin-td">
                    <button
                      type="button"
                      role="switch"
                      :aria-checked="isFeatured(p)"
                      :disabled="togglingId === p.id"
                      aria-label="Feature product On/Off"
                      class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                      :class="isFeatured(p) ? 'bg-amber-500' : 'bg-zinc-300 dark:bg-zinc-600'"
                      @click="toggleFeatured(p)"
                    >
                      <span
                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition-transform"
                        :class="isFeatured(p) ? 'translate-x-6' : 'translate-x-1'"
                      />
                    </button>
                    <span class="ml-2 text-sm font-medium" :class="isFeatured(p) ? 'text-amber-600 dark:text-amber-400' : 'text-zinc-500 dark:text-zinc-400'">
                      {{ isFeatured(p) ? 'On' : 'Off' }}
                    </span>
                  </td>
                  <td class="admin-td admin-td-last text-right">
                    <div class="flex items-center justify-end gap-1">
                      <Link :href="route('admin.products.edit', p.id)" class="admin-action admin-action-edit" title="Edit">
                        <Pencil class="h-4 w-4" stroke-width="2" />
                        <span class="hidden sm:inline">Edit</span>
                      </Link>
                      <span class="admin-action-divider" aria-hidden="true" />
                      <button type="button" class="admin-action admin-action-delete" title="Delete" @click="destroy(p.id, p.name)">
                        <Trash2 class="h-4 w-4" stroke-width="2" />
                        <span class="hidden sm:inline">Delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
        <EmptyState v-else :icon="Package" title="No products yet" description="Add your first product to display on the site.">
          <template #actions>
            <Link :href="route('admin.products.create')" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">
              <Plus class="h-4 w-4" stroke-width="2" /> Add product
            </Link>
          </template>
        </EmptyState>
        <PaginationBar :data="products" />
      </DataCard>
    </div>
  </AdminLayout>
</template>
