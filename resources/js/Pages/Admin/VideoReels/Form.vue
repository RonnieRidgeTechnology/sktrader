<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import DataCard from '@/Components/Admin/DataCard.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import { Video, Upload } from 'lucide-vue-next';

const props = defineProps({ reel: Object });

const form = useForm({
  title: props.reel?.title ?? '',
  file_path: props.reel?.file_path ?? '',
  sort_order: props.reel?.sort_order ?? 0,
  status: props.reel !== undefined && props.reel !== null ? props.reel.status : true,
  video: null,
  _method: props.reel ? 'PUT' : 'POST',
});
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="reel ? 'Edit video reel' : 'New video reel'"
        description="Reels appear in the homepage and video section. Use a filename from public/media/video/ or upload a new video."
      >
        <template #actions>
          <Link
            :href="route('admin.video-reels.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
          <button
            type="submit"
            form="reel-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-70 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
          >
            {{ reel ? 'Update' : 'Create' }}
          </button>
        </template>
      </PageHeader>

      <form
        id="reel-form"
        class="space-y-8"
        @submit.prevent="form.post(reel ? route('admin.video-reels.update', reel.id) : route('admin.video-reels.store'), { forceFormData: true })"
      >
        <FormCard
          title="Reel details"
          description="Title is shown as a label. File path is the filename in public/media/video/ (e.g. 1.mp4 or 2)."
        >
          <template #vector>
            <Video class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-5">
            <div>
              <label for="title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Title *</label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. Factory tour"
              />
              <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">{{ form.errors.title }}</p>
            </div>
            <div>
              <label for="file_path" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">File path *</label>
              <input
                id="file_path"
                v-model="form.file_path"
                type="text"
                required
                class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 font-mono text-sm text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                placeholder="e.g. 1.mp4 or 1"
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Filename in public/media/video/. Upload below to save a new file and update this path.</p>
              <p v-if="form.errors.file_path" class="mt-1 text-sm text-red-500">{{ form.errors.file_path }}</p>
            </div>
            <div>
              <label for="sort_order" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Sort order</label>
              <input
                id="sort_order"
                v-model.number="form.sort_order"
                type="number"
                min="0"
                class="mt-2 block w-full max-w-[8rem] rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
              />
            </div>
          </div>
        </FormCard>

        <FormCard
          title="Replace video (optional)"
          description="Upload a new file to save in public/media/video/ and update the file path automatically."
        >
          <template #vector>
            <Upload class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div>
            <label for="video" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Video file</label>
            <input
              id="video"
              type="file"
              accept="video/mp4,video/webm,video/quicktime"
              class="mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-200 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:file:bg-zinc-700 dark:file:text-zinc-300"
              @change="form.video = $event.target.files?.[0]"
            />
          </div>
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
              {{ reel ? 'Update reel' : 'Create reel' }}
            </button>
          </div>
        </DataCard>
      </form>
    </div>
  </AdminLayout>
</template>
