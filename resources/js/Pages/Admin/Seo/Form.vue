<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import Dropzone from '@/Components/Admin/Dropzone.vue';
import { Search, Image as ImageIcon } from 'lucide-vue-next';

const props = defineProps({ setting: Object });

const form = useForm({
  meta_title: props.setting?.meta_title ?? '',
  meta_description: props.setting?.meta_description ?? '',
  meta_keywords: props.setting?.meta_keywords ?? '',
  canonical_url: props.setting?.canonical_url ?? '',
  og_image: props.setting?.og_image ?? '',
  og_type: props.setting?.og_type ?? 'website',
  noindex: props.setting?.noindex ?? false,
  og_image_file: null,
  _method: 'PUT',
});
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="'Edit SEO: ' + (setting?.page_key || '')"
        description="Meta title and description affect how this page appears in search results. Keep title under 60 characters and description 150–160."
      >
        <template #actions>
          <Link
            :href="route('admin.seo.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
          <button
            type="submit"
            form="seo-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            Update
          </button>
        </template>
      </PageHeader>

      <form
        id="seo-form"
        class="space-y-8"
        @submit.prevent="form.post(route('admin.seo.update', setting.id), { forceFormData: true })"
      >
        <FormCard
          title="Meta tags"
          description="Meta title (≈60 chars) and description (150–160 chars) are shown in search results. Keywords are optional."
        >
          <template #vector>
            <Search class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-5">
            <div>
              <label for="meta_title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Meta title</label>
              <input
                id="meta_title"
                v-model="form.meta_title"
                type="text"
                maxlength="70"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. Sofas & Furniture Zambia | Atlantic Garden Furniture"
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Recommended length: 50–60 characters.</p>
              <p v-if="form.errors.meta_title" class="mt-1 text-sm text-red-500">{{ form.errors.meta_title }}</p>
            </div>
            <div>
              <label for="meta_description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Meta description</label>
              <textarea
                id="meta_description"
                v-model="form.meta_description"
                rows="3"
                maxlength="170"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="Brief description for search results..."
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Recommended: 150–160 characters.</p>
              <p v-if="form.errors.meta_description" class="mt-1 text-sm text-red-500">{{ form.errors.meta_description }}</p>
            </div>
            <div>
              <label for="meta_keywords" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Meta keywords</label>
              <input
                id="meta_keywords"
                v-model="form.meta_keywords"
                type="text"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="Comma-separated keywords"
              />
            </div>
          </div>
        </FormCard>

        <FormCard
          title="Canonical, OG image &amp; indexing"
          description="Canonical URL and OG image for sharing. Use noindex for pages you don't want in search results."
        >
          <template #vector>
            <ImageIcon class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-5">
            <div>
              <label for="canonical_url" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Canonical URL</label>
              <input
                id="canonical_url"
                v-model="form.canonical_url"
                type="url"
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="https://..."
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Leave empty to use the current page URL.</p>
            </div>
            <div class="flex flex-wrap gap-6">
              <div>
                <label for="og_type" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">OG type</label>
                <select
                  id="og_type"
                  v-model="form.og_type"
                  class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                >
                  <option value="website">website</option>
                  <option value="article">article</option>
                </select>
              </div>
              <label class="flex cursor-pointer items-center gap-3 pt-8">
                <input
                  v-model="form.noindex"
                  type="checkbox"
                  class="h-4 w-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800"
                />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Noindex (hide from search engines)</span>
              </label>
            </div>
            <div>
              <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">OG image (upload)</label>
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Image shown when this page is shared (e.g. Facebook, LinkedIn).</p>
              <div class="mt-3">
                <Dropzone
                  :model-value="form.og_image_file"
                  accept="image/*"
                  label="Drop image or click to upload"
                  hint="PNG or JPG, recommended 1200×630px"
                  @update:model-value="form.og_image_file = $event"
                />
              </div>
            </div>
          </div>
        </FormCard>

        <DataCard class="p-6">
          <div class="flex justify-end gap-3">
            <Link
              :href="route('admin.seo.index')"
              class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
            >
              Update SEO
            </button>
          </div>
        </DataCard>
      </form>
    </div>
  </AdminLayout>
</template>
