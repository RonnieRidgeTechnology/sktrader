<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import Dropzone from '@/Components/Admin/Dropzone.vue';
import SearchableSelect from '@/Components/Admin/SearchableSelect.vue';
import { FolderTree, Image as ImageIcon } from 'lucide-vue-next';

const props = defineProps({
  category: { type: Object, default: null },
  categories: { type: Array, default: () => [] },
  presetParentId: { type: Number, default: null },
});

const form = useForm({
  parent_id: props.category?.parent_id ?? (props.presetParentId ?? ''),
  name: props.category?.name ?? '',
  description: props.category?.description ?? '',
  status: props.category !== null ? props.category.status : true,
  image: null,
  _method: props.category ? 'PUT' : 'POST',
});

const isAddSubCategory = computed(() => !props.category && props.presetParentId != null);

// First option is "Parent" (no parent = top-level category); then all selectable categories
const parentOptions = computed(() => [
  { id: '', name: 'Parent' },
  ...(props.categories || []).map((c) => ({ id: c.id, name: c.name })),
]);
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="category ? 'Edit category' : 'New category'"
        description="Categories organize your products. Use a clear name; the slug is generated automatically."
      >
        <template #actions>
          <Link
            :href="route('admin.categories.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
          <button
            type="submit"
            form="category-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            {{ category ? 'Update' : 'Create' }}
          </button>
        </template>
      </PageHeader>

      <form
        id="category-form"
        class="space-y-8"
        @submit.prevent="form.post(category ? route('admin.categories.update', category.id) : route('admin.categories.store'), { forceFormData: true })"
      >
        <FormCard
          title="Category details"
          description="Name and description help customers browse your catalog. The URL slug is auto-generated from the name."
        >
          <template #vector>
            <FolderTree class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-5">
            <div v-if="!category && !isAddSubCategory">
              <SearchableSelect
                v-model="form.parent_id"
                label="Parent category"
                placeholder="Parent"
                search-placeholder="Search categories..."
                :options="parentOptions"
                value-key="id"
                label-key="name"
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Leave as &quot;Parent&quot; to create a top-level category. Select a category to create a subcategory.</p>
              <p v-if="form.errors.parent_id" class="mt-1 text-sm text-red-500">{{ form.errors.parent_id }}</p>
            </div>
            <div v-if="!category && isAddSubCategory" class="rounded-xl border border-zinc-200 bg-zinc-50/50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800/50">
              <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Parent category</p>
              <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                {{ (categories || []).find(c => c.id === presetParentId)?.name ?? 'Selected parent' }}
              </p>
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">This new category will be created under the selected parent.</p>
              <p v-if="form.errors.parent_id" class="mt-1 text-sm text-red-500">{{ form.errors.parent_id }}</p>
            </div>
            <div v-if="category" class="rounded-xl border border-zinc-200 bg-zinc-50/50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800/50">
              <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Parent category</p>
              <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ category.parent ? category.parent.name : 'Parent (top-level)' }}</p>
            </div>
            <div>
              <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name *</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. Watches, Perfumes, Serums"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Description</label>
              <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="Optional short description"
              />
            </div>
          </div>
        </FormCard>

        <FormCard
          title="Image"
          description="Optional image for this category (e.g. for category pages or menus)."
        >
          <template #vector>
            <ImageIcon class="h-12 w-12" stroke-width="1.5" />
          </template>
          <Dropzone
            :model-value="form.image"
            accept="image/*"
            label="Drop image or click to upload"
            hint="PNG, JPG, WebP"
            @update:model-value="form.image = $event"
          />
        </FormCard>

        <DataCard class="p-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <label class="flex cursor-pointer items-center gap-3">
              <input
                v-model="form.status"
                type="checkbox"
                class="h-4 w-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-700"
              />
              <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active (show on site)</span>
            </label>
            <button
              type="submit"
              :disabled="form.processing"
              class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
            >
              {{ category ? 'Update category' : 'Create category' }}
            </button>
          </div>
        </DataCard>
      </form>
    </div>
  </AdminLayout>
</template>
