<script setup>
import { computed, ref, watch } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import { ScrollText, Plus, Trash2 } from 'lucide-vue-next';
import { storageUrl } from '@/utils/storageUrl';

/** Pending file uploads for image fields: { [fieldKey]: File } */
const pendingImageFiles = ref({});

const props = defineProps({
  pageKey: String,
  pageName: String,
  fields: Array,
  content: Object,
});

const inputClass =
  'mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white';
const labelClass = 'block text-sm font-medium text-zinc-700 dark:text-zinc-300';

function getDefaultSteps(fields) {
  const stepsField = fields?.find((f) => f.type === 'steps');
  const sub = stepsField?.sub_fields || ['title', 'body', 'bullets'];
  return [
    Object.fromEntries(sub.map((s) => [s, s === 'bullets' ? [].join('\n') : ''])),
  ];
}

function buildInitialContent() {
  const content = props.content && typeof props.content === 'object' ? props.content : {};
  const out = {};
  for (const f of props.fields || []) {
    if (f.type === 'steps') {
      const saved = content[f.key];
      out[f.key] = Array.isArray(saved) && saved.length
        ? saved.map((s) => ({
            ...s,
            bullets: Array.isArray(s.bullets) ? s.bullets.join('\n') : (s.bullets ?? ''),
          }))
        : getDefaultSteps(props.fields);
    } else if (f.type === 'image') {
      out[f.key] = content[f.key] !== undefined && content[f.key] !== null ? content[f.key] : '';
    } else {
      out[f.key] = content[f.key] !== undefined && content[f.key] !== null ? content[f.key] : '';
    }
  }
  return out;
}

function imageUrl(fieldKey) {
  const path = form.content[fieldKey];
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
}

function setPendingImage(fieldKey, file) {
  pendingImageFiles.value[fieldKey] = file || null;
  if (file) form.content[fieldKey] = ''; // clear path so UI shows "new image selected"
}

function clearImage(fieldKey) {
  form.content[fieldKey] = '';
  pendingImageFiles.value[fieldKey] = null;
}

const hasPendingImage = computed(() => Object.values(pendingImageFiles.value).some(Boolean));

const form = useForm({
  content: buildInitialContent(),
});

// Sync form from server content on mount and when props change (e.g. after save redirect)
watch(
  () => [props.content, props.fields],
  () => {
    const next = buildInitialContent();
    form.content = { ...next };
    pendingImageFiles.value = {};
  },
  { deep: true, immediate: true }
);

function addStep(fieldKey) {
  const stepsField = props.fields.find((f) => f.key === fieldKey && f.type === 'steps');
  const sub = stepsField?.sub_fields || ['title', 'body', 'bullets'];
  const newStep = Object.fromEntries(sub.map((s) => [s, s === 'bullets' ? '' : '']));
  form.content[fieldKey] = [...(form.content[fieldKey] || []), newStep];
}

function removeStep(fieldKey, index) {
  const arr = [...(form.content[fieldKey] || [])];
  arr.splice(index, 1);
  form.content[fieldKey] = arr.length ? arr : getDefaultSteps(props.fields);
}

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? null);

function submit() {
  const url = route('admin.page-content.update', props.pageKey);
  if (hasPendingImage.value) {
    const fd = new FormData();
    fd.append('content', JSON.stringify(form.content));
    fd.append('_method', 'PUT');
    for (const key of Object.keys(pendingImageFiles.value)) {
      const file = pendingImageFiles.value[key];
      if (file) fd.append(key, file);
    }
    router.post(url, fd, { forceFormData: true, preserveScroll: true });
  } else {
    form.put(url, { preserveScroll: true });
  }
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-8">
      <PageHeader
        :title="'Edit: ' + (pageName || pageKey)"
        description="Update the content shown on this page. Save to publish changes to the live site."
      >
        <template #actions>
          <Link
            :href="route('admin.page-content.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Back to pages
          </Link>
          <button
            type="button"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-amber-400 disabled:opacity-70"
            @click="submit()"
          >
            {{ form.processing ? 'Saving…' : 'Save content' }}
          </button>
        </template>
      </PageHeader>

      <div v-if="flashSuccess" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
        {{ flashSuccess }}
      </div>

      <form v-if="fields?.length" class="space-y-8" @submit.prevent="submit()">
        <FormCard
          title="Page content"
          :description="'Fields for ' + (pageName || pageKey) + '. Edit and save.'"
        >
          <template #vector>
            <ScrollText class="h-12 w-12" stroke-width="1.5" />
          </template>

          <div class="space-y-6">
            <template v-for="field in fields" :key="field.key">
              <!-- Text -->
              <div v-if="field.type === 'text'">
                <label :for="'f-' + field.key" :class="labelClass">{{ field.label }}</label>
                <input
                  :id="'f-' + field.key"
                  v-model="form.content[field.key]"
                  type="text"
                  :class="inputClass"
                  :placeholder="field.placeholder || ''"
                />
                <p v-if="form.errors['content.' + field.key]" class="mt-1 text-sm text-red-500">
                  {{ form.errors['content.' + field.key] }}
                </p>
              </div>

              <!-- Textarea -->
              <div v-else-if="field.type === 'textarea'">
                <label :for="'f-' + field.key" :class="labelClass">{{ field.label }}</label>
                <textarea
                  :id="'f-' + field.key"
                  v-model="form.content[field.key]"
                  :class="inputClass"
                  :rows="field.rows || 3"
                  :placeholder="field.placeholder || ''"
                />
                <p v-if="form.errors['content.' + field.key]" class="mt-1 text-sm text-red-500">
                  {{ form.errors['content.' + field.key] }}
                </p>
              </div>

              <!-- Image -->
              <div v-else-if="field.type === 'image'" class="space-y-2">
                <label :class="labelClass">{{ field.label }}</label>
                <p v-if="field.help" class="mb-1 text-xs text-zinc-500 dark:text-zinc-400">{{ field.help }}</p>
                <input
                  type="file"
                  accept="image/*"
                  class="block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                  @change="setPendingImage(field.key, $event.target.files?.[0] ?? null); $event.target.value = ''"
                />
                <div v-if="imageUrl(field.key) && !pendingImageFiles[field.key]" class="mt-3 flex flex-wrap items-center gap-4">
                  <img :src="imageUrl(field.key)" :alt="field.label" class="max-h-32 rounded-lg border border-zinc-200 object-cover dark:border-zinc-600" />
                  <button type="button" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" @click="clearImage(field.key)">
                    Remove image
                  </button>
                </div>
                <p v-if="pendingImageFiles[field.key]" class="mt-2 text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
              </div>

              <!-- Steps (repeater) -->
              <div v-else-if="field.type === 'steps'" class="space-y-4">
                <div class="flex items-center justify-between">
                  <label :class="labelClass">{{ field.label }}</label>
                  <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-100 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600"
                    @click="addStep(field.key)"
                  >
                    <Plus class="h-4 w-4" stroke-width="2" />
                    Add item
                  </button>
                </div>
                <div
                  v-for="(step, index) in (form.content[field.key] || [])"
                  :key="index"
                  class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/30"
                >
                  <div class="mb-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-400">Item {{ index + 1 }}</span>
                    <button
                      type="button"
                      class="rounded p-1.5 text-zinc-500 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400"
                      :disabled="(form.content[field.key] || []).length <= 1"
                      title="Remove"
                      @click="removeStep(field.key, index)"
                    >
                      <Trash2 class="h-4 w-4" stroke-width="2" />
                    </button>
                  </div>
                  <div class="space-y-3">
                    <div v-for="sub in (field.sub_fields || [])" :key="sub" class="space-y-1">
                      <label :class="['text-xs font-medium text-zinc-500 dark:text-zinc-400', sub === 'body' ? 'block' : '']">
                        {{ sub.charAt(0).toUpperCase() + sub.slice(1) }}
                      </label>
                      <textarea
                        v-if="sub === 'body' || sub === 'bullets'"
                        v-model="step[sub]"
                        :class="inputClass"
                        :rows="sub === 'bullets' ? 2 : 3"
                        :placeholder="sub === 'bullets' ? 'One bullet per line' : ''"
                      />
                      <input
                        v-else
                        v-model="step[sub]"
                        type="text"
                        :class="inputClass"
                        :placeholder="sub"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </FormCard>

        <div class="flex flex-wrap gap-3">
          <button
            type="submit"
            :disabled="form.processing"
            class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-amber-400 disabled:opacity-70"
          >
            {{ form.processing ? 'Saving…' : 'Save content' }}
          </button>
          <Link
            :href="route('admin.page-content.index')"
            class="rounded-xl border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
        </div>
      </form>

      <p v-else class="rounded-2xl border border-zinc-200 bg-white p-6 text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
        No editable fields defined for this page.
      </p>
    </div>
  </AdminLayout>
</template>
