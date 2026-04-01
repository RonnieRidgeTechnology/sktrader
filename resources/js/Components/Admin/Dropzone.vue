<script setup>
import { ref, onBeforeUnmount } from 'vue';
import { Upload, X } from 'lucide-vue-next';

const props = defineProps({
  modelValue: { type: [Object, Array], default: null },
  accept: { type: String, default: 'image/*' },
  multiple: { type: Boolean, default: false },
  label: { type: String, default: 'Drop files here or click to upload' },
  hint: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const inputEl = ref(null);
const isDragging = ref(false);
const inputId = ref('dz-' + Math.random().toString(36).slice(2, 11));

// Single source of truth: { file, previewUrl }. We never read from parent — preview can't disappear.
const items = ref([]);

function addFiles(fileListLike) {
  if (!fileListLike?.length) return;
  const files = Array.from(fileListLike);
  const newItems = files
    .filter((f) => f && f instanceof File && (f.type || '').startsWith('image/'))
    .map((file) => ({
      file,
      previewUrl: URL.createObjectURL(file),
    }));
  if (newItems.length === 0) return;
  if (props.multiple) {
    items.value = [...items.value, ...newItems];
  } else {
    // Single mode: revoke previous and replace
    items.value.forEach((it) => {
      try { URL.revokeObjectURL(it.previewUrl); } catch (_) {}
    });
    items.value = [newItems[0]];
  }
  emitValue();
  if (inputEl.value) inputEl.value.value = '';
}

function emitValue() {
  if (props.multiple) {
    emit('update:modelValue', items.value.map((it) => it.file));
  } else {
    emit('update:modelValue', items.value[0]?.file ?? null);
  }
}

function remove(index) {
  const item = items.value[index];
  if (item) {
    try { URL.revokeObjectURL(item.previewUrl); } catch (_) {}
  }
  items.value = items.value.filter((_, i) => i !== index);
  emitValue();
  if (inputEl.value) inputEl.value.value = '';
}

function onInputChange(e) {
  const target = e?.target;
  if (!target?.files?.length) return;
  addFiles(target.files);
}

function openFileDialog() {
  inputEl.value?.click();
}

function onDrop(e) {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = false;
  if (e.dataTransfer?.files?.length) addFiles(e.dataTransfer.files);
}

function onDragOver(e) {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = true;
}

function onDragLeave(e) {
  e.preventDefault();
  e.stopPropagation();
  isDragging.value = false;
}

onBeforeUnmount(() => {
  items.value.forEach((it) => {
    try { URL.revokeObjectURL(it.previewUrl); } catch (_) {}
  });
  items.value = [];
});
</script>

<template>
  <div class="space-y-4">
    <!-- Click/drop zone -->
    <div
      class="relative flex min-h-[160px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed transition"
      :class="isDragging
        ? 'border-amber-400 bg-amber-50/50 dark:border-amber-500 dark:bg-amber-900/20'
        : 'border-zinc-300 bg-zinc-50/50 dark:border-zinc-600 dark:bg-zinc-800/50 hover:border-zinc-400 hover:bg-zinc-100 dark:hover:border-zinc-500 dark:hover:bg-zinc-800'"
      @click="openFileDialog"
      @drop.prevent="onDrop"
      @dragover.prevent="onDragOver"
      @dragleave.prevent="onDragLeave"
    >
      <input
        :id="inputId"
        ref="inputEl"
        type="file"
        :accept="accept"
        :multiple="multiple"
        class="absolute h-0 w-0 overflow-hidden opacity-0"
        tabindex="-1"
        aria-hidden="true"
        @change="onInputChange"
      />
      <div class="flex flex-col items-center justify-center px-6 py-8 text-center pointer-events-none">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white dark:bg-zinc-700">
          <Upload class="h-6 w-6 text-zinc-500 dark:text-zinc-400" stroke-width="2" />
        </div>
        <p class="mt-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ label }}</p>
        <p v-if="hint" class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ hint }}</p>
        <p class="mt-1 text-xs font-medium text-amber-600 dark:text-amber-400">Click or drop images here</p>
      </div>
    </div>

    <!-- Previews: image with X on the picture -->
    <div v-if="items.length" class="flex flex-wrap gap-4">
      <div
        v-for="(item, i) in items"
        :key="item.previewUrl + '-' + (item.file?.name ?? '') + '-' + (item.file?.size ?? 0)"
        class="group relative inline-block"
      >
        <div class="relative h-28 w-28 overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
          <img
            :src="item.previewUrl"
            :alt="item.file?.name ?? 'Preview'"
            class="h-full w-full object-cover"
          />
          <button
            type="button"
            class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-white shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
            title="Remove"
            aria-label="Remove image"
            @click.prevent.stop="remove(i)"
          >
            <X class="h-4 w-4" stroke-width="2.5" />
          </button>
        </div>
        <p class="mt-1 max-w-[7rem] truncate text-center text-xs text-zinc-500 dark:text-zinc-400">
          {{ item.file?.name ?? 'Image' }}
        </p>
      </div>
    </div>
  </div>
</template>
