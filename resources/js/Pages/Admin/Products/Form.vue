<script setup>
import { ref, computed, watch, nextTick, inject } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import Dropzone from '@/Components/Admin/Dropzone.vue';
import SearchableSelect from '@/Components/Admin/SearchableSelect.vue';
import RichTextEditor from '@/Components/Admin/RichTextEditor.vue';

const route = inject('route') ?? (typeof window !== 'undefined' && window.route ? window.route : () => null);

const props = defineProps({
  product: Object,
  categories: Array,
});

const categoryOptions = computed(() => {
  const list = props.categories || [];
  return list.map((c) => ({
    id: c.id,
    name: c.parent ? `${c.parent.name} › ${c.name}` : c.name,
  }));
});

const page = usePage();
const form = useForm({
  _token: page.props.csrf_token ?? '',
  category_id: props.product?.category_id ?? (props.categories?.[0]?.id ?? ''),
  name: props.product?.name ?? '',
  short_description: props.product?.short_description ?? '',
  description: props.product?.description ?? '',
  price: props.product?.price ?? 0,
  stock: props.product?.stock ?? null,
  status: props.product !== undefined && props.product !== null ? props.product.status : true,
  condition: props.product?.condition ?? 'new',
  image: null,
  gallery: [],
  _method: props.product ? 'PUT' : 'POST',
});

const conditionOptions = [
  { value: 'new', label: 'New' },
  { value: 'pre_owned', label: 'Pre-owned' },
];

function setMainImage(file) {
  form.image = file ?? null;
}

function setGallery(files) {
  form.gallery = Array.isArray(files) ? files : files ? [files] : [];
}

function cancelToProducts() {
  if (route) router.visit(route('admin.products.index'), { preserveState: false });
}

const errorSummaryRef = ref(null);

function firstError(errors, key) {
  const v = errors?.[key];
  return Array.isArray(v) ? v[0] : v;
}

function allErrorMessages(errors) {
  if (!errors || typeof errors !== 'object') return [];
  return Object.entries(errors).map(([key, val]) => ({
    key,
    msg: Array.isArray(val) ? val[0] : val,
  })).filter(({ msg }) => msg);
}

watch(
  () => form.errors,
  (errors) => {
    if (allErrorMessages(errors).length > 0) {
      nextTick(() => errorSummaryRef.value?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    }
  },
  { deep: true }
);

function submitProduct() {
  if (!props.product && (form.image === null || form.image === undefined)) {
    form.setError('image', 'Please upload a main image.');
    nextTick(() => errorSummaryRef.value?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    return;
  }
  form._token = page.props.csrf_token ?? '';
  const url = route ? (props.product ? route('admin.products.update', props.product.id) : route('admin.products.store')) : null;
  if (!url) return;
  form.post(url, { forceFormData: true });
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <PageHeader
        :title="product ? 'Edit product' : 'New product'"
        :description="product ? 'Update product.' : 'Add a product.'"
      >
        <template #actions>
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
            @click="cancelToProducts"
          >
            Cancel
          </button>
          <button
            type="submit"
            form="product-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            {{ product ? 'Update' : 'Create' }}
          </button>
        </template>
      </PageHeader>

      <form id="product-form" class="space-y-6" @submit.prevent="submitProduct">
        <div
          v-if="allErrorMessages(form.errors).length > 0"
          ref="errorSummaryRef"
          class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20"
          role="alert"
        >
          <p class="text-sm font-semibold text-red-800 dark:text-red-200">Please fix the following:</p>
          <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-300">
            <li v-for="item in allErrorMessages(form.errors)" :key="item.key">{{ item.msg }}</li>
          </ul>
        </div>

        <DataCard class="p-6 lg:p-8">
          <div class="space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <SearchableSelect
                  v-model="form.category_id"
                  label="Category *"
                  :options="categoryOptions"
                  placeholder="Choose category"
                  search-placeholder="Search..."
                  value-key="id"
                  label-key="name"
                  required
                />
                <p v-if="firstError(form.errors, 'category_id')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'category_id') }}</p>
              </div>
              <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name *</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="mt-1.5 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                  placeholder="Product name"
                />
                <p v-if="firstError(form.errors, 'name')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'name') }}</p>
              </div>
              <div class="sm:col-span-2">
                <label for="short_description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Short description</label>
                <input
                  id="short_description"
                  v-model="form.short_description"
                  type="text"
                  class="mt-1.5 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                  placeholder="One line for listings"
                />
              </div>
              <div>
                <label for="price" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Price *</label>
                <input
                  id="price"
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="mt-1.5 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                  placeholder="0"
                />
                <p v-if="firstError(form.errors, 'price')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'price') }}</p>
              </div>
              <div>
                <label for="stock" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Stock</label>
                <input
                  id="stock"
                  v-model.number="form.stock"
                  type="number"
                  min="0"
                  class="mt-1.5 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                  placeholder="Optional"
                />
              </div>
              <div>
                <label for="condition" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Condition *</label>
                <select
                  id="condition"
                  v-model="form.condition"
                  required
                  class="mt-1.5 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                >
                  <option v-for="opt in conditionOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">New or pre-owned (previously used).</p>
                <p v-if="firstError(form.errors, 'condition')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'condition') }}</p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Full description</label>
              <RichTextEditor
                v-model="form.description"
                placeholder="Product description (bold, lists, etc.)"
                class="mt-1.5"
                min-height="180px"
              />
              <p v-if="firstError(form.errors, 'description')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'description') }}</p>
            </div>

            <div class="grid gap-6 border-t border-zinc-200 pt-6 dark:border-zinc-700 md:grid-cols-2">
              <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Main image *</label>
                <div class="mt-1.5">
                  <Dropzone
                    :model-value="form.image"
                    accept="image/*"
                    label="Drop or click"
                    hint="PNG, JPG, WebP"
                    @update:model-value="setMainImage($event)"
                  />
                </div>
                <p v-if="firstError(form.errors, 'image')" class="mt-1 text-sm text-red-500">{{ firstError(form.errors, 'image') }}</p>
                <p v-if="product?.image && !form.errors.image" class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Current: {{ product.image }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Gallery</label>
                <div class="mt-1.5">
                  <Dropzone
                    :model-value="form.gallery"
                    accept="image/*"
                    multiple
                    label="Drop or click"
                    hint="Multiple"
                    @update:model-value="setGallery($event)"
                  />
                </div>
              </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-zinc-200 pt-6 dark:border-zinc-700">
              <label class="flex items-center gap-2">
                <input
                  v-model="form.status"
                  type="checkbox"
                  class="h-4 w-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-700"
                />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active</span>
              </label>
              <div class="flex gap-3">
                <button type="button" class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800" @click="cancelToProducts">
                  Cancel
                </button>
                <button type="submit" :disabled="form.processing" class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
                  {{ product ? 'Update' : 'Create' }}
                </button>
              </div>
            </div>
          </div>
        </DataCard>
      </form>
    </div>
  </AdminLayout>
</template>
