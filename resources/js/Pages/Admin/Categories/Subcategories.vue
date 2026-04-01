<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import EmptyState from '@/Components/Admin/EmptyState.vue';
import { FolderTree, Plus, Pencil, Trash2, Check, X, GripVertical, Search, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
  parentCategory: { type: Object, required: true },
  subcategories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({ q: '' }) },
});

const searchQuery = ref(props.filters?.q ?? '');

function getSiblingIds() {
  return props.subcategories.map((c) => c.id);
}

const dragged = ref(null);

function onDragStart(e, cat) {
  dragged.value = { id: cat.id, parent_id: cat.parent_id ?? null };
  e.dataTransfer.setData('text/plain', cat.id);
  e.dataTransfer.effectAllowed = 'move';
  e.target.closest('tr')?.classList.add('opacity-50');
}

function onDragEnd(e) {
  e.target.closest('tr')?.classList.remove('opacity-50');
  dragged.value = null;
}

function onDragOver(e, cat) {
  e.preventDefault();
  if (!dragged.value) return;
  const targetParent = cat.parent_id ?? null;
  if (dragged.value.parent_id !== targetParent) {
    e.dataTransfer.dropEffect = 'none';
    return;
  }
  e.dataTransfer.dropEffect = 'move';
}

function onDrop(e, dropTargetCat) {
  e.preventDefault();
  if (!dragged.value || dragged.value.id === dropTargetCat.id) return;
  const targetParent = dropTargetCat.parent_id ?? null;
  if (dragged.value.parent_id !== targetParent) return;

  const siblingIds = getSiblingIds();
  const fromIndex = siblingIds.indexOf(dragged.value.id);
  const toIndex = siblingIds.indexOf(dropTargetCat.id);
  if (fromIndex === -1 || toIndex === -1) return;

  const newOrder = [...siblingIds];
  newOrder.splice(fromIndex, 1);
  newOrder.splice(toIndex, 0, dragged.value.id);

  router.post(route('admin.categories.reorder'), {
    parent_id: props.parentCategory.id,
    ordered_ids: newOrder,
    return_to: 'subcategories',
    q: props.filters?.q || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
  dragged.value = null;
}

function applySearch() {
  const params = {};
  if (searchQuery.value && searchQuery.value.trim()) params.q = searchQuery.value.trim();
  router.get(route('admin.categories.subcategories', props.parentCategory.id), params, { preserveState: true });
}

const total = computed(() => props.subcategories?.length ?? 0);

const addSubCategoryUrl = computed(() => {
  return `${route('admin.categories.create')}?parent_id=${props.parentCategory.id}`;
});

function destroy(id, name) {
  if (confirm(`Delete sub-category "${name}"? This may affect products.`)) {
    router.delete(route('admin.categories.destroy', id));
  }
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        :title="`Sub-categories of “${parentCategory.name}”`"
        description="Manage sub-categories. Drag rows to change order."
      >
        <template #actions>
          <Link
            :href="route('admin.categories.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            <ArrowLeft class="h-4 w-4" stroke-width="2" />
            Back to categories
          </Link>
          <Link
            :href="addSubCategoryUrl"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            <Plus class="h-4 w-4" stroke-width="2" />
            Add sub-category
          </Link>
        </template>
      </PageHeader>

      <!-- Search -->
      <DataCard class="p-4">
        <div class="flex flex-nowrap items-end gap-4">
          <div class="min-w-0 flex-1">
            <label for="subcat-search" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Search</label>
            <div class="relative mt-1">
              <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" stroke-width="2" />
              <input
                id="subcat-search"
                v-model="searchQuery"
                type="search"
                placeholder="Name or slug..."
                class="block w-full rounded-xl border border-zinc-300 bg-white py-2.5 pl-10 pr-4 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                @keydown.enter="applySearch"
              />
            </div>
          </div>
          <button
            type="button"
            class="shrink-0 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
            @click="applySearch"
          >
            Apply
          </button>
        </div>
      </DataCard>

      <DataCard class="p-0">
        <template v-if="subcategories?.length">
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/40">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <span class="font-semibold text-zinc-900 dark:text-white">{{ total }}</span>
              {{ total === 1 ? 'sub-category' : 'sub-categories' }}
            </p>
          </div>

          <div class="overflow-x-auto">
            <table class="admin-table min-w-full">
              <thead>
                <tr>
                  <th scope="col" class="admin-th admin-th-first w-10" aria-label="Drag" />
                  <th scope="col" class="admin-th">Category</th>
                  <th scope="col" class="admin-th">Slug</th>
                  <th scope="col" class="admin-th">Status</th>
                  <th scope="col" class="admin-th admin-th-last text-right">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-zinc-200/80 dark:divide-zinc-700/80">
                <tr
                  v-for="cat in subcategories"
                  :key="cat.id"
                  class="admin-row group transition-colors"
                  draggable="true"
                  @dragstart="onDragStart($event, cat)"
                  @dragend="onDragEnd"
                  @dragover="onDragOver($event, cat)"
                  @drop="onDrop($event, cat)"
                >
                  <td class="admin-td admin-td-first w-10 cursor-grab active:cursor-grabbing">
                    <div
                      class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg text-zinc-400 hover:bg-zinc-200 hover:text-zinc-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300"
                      title="Drag to reorder"
                    >
                      <GripVertical class="h-5 w-5" stroke-width="2" />
                    </div>
                  </td>
                  <td class="admin-td">
                    <div class="flex items-center gap-4">
                      <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 transition group-hover:bg-amber-100 group-hover:text-amber-700 dark:bg-zinc-700 dark:text-zinc-300 dark:group-hover:bg-amber-900/30 dark:group-hover:text-amber-400"
                        aria-hidden="true"
                      >
                        <FolderTree class="h-5 w-5" stroke-width="2" />
                      </div>
                      <span class="font-semibold text-zinc-900 dark:text-white">{{ cat.name }}</span>
                    </div>
                  </td>
                  <td class="admin-td">
                    <code class="slug-pill rounded-lg border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                      {{ cat.slug }}
                    </code>
                  </td>
                  <td class="admin-td">
                    <span
                      :class="cat.status
                        ? 'status-badge status-badge-active'
                        : 'status-badge status-badge-inactive'"
                    >
                      <component :is="cat.status ? Check : X" class="h-3.5 w-3.5 shrink-0" stroke-width="2.5" />
                      {{ cat.status ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="admin-td admin-td-last text-right">
                    <div class="flex items-center justify-end gap-1">
                      <Link
                        :href="route('admin.categories.edit', cat.id)"
                        class="admin-action admin-action-edit"
                        title="Edit"
                        @click.stop
                      >
                        <Pencil class="h-4 w-4" stroke-width="2" />
                        <span class="hidden sm:inline">Edit</span>
                      </Link>
                      <span class="admin-action-divider" aria-hidden="true" />
                      <button
                        type="button"
                        class="admin-action admin-action-delete"
                        title="Delete"
                        @click.stop="destroy(cat.id, cat.name)"
                      >
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

        <EmptyState
          v-else
          :icon="FolderTree"
          title="No sub-categories yet"
          :description="`Add sub-categories under “${parentCategory.name}”.`"
        >
          <template #actions>
            <Link
              :href="addSubCategoryUrl"
              class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
            >
              <Plus class="h-4 w-4" stroke-width="2" />
              Add sub-category
            </Link>
          </template>
        </EmptyState>
      </DataCard>
    </div>
  </AdminLayout>
</template>
