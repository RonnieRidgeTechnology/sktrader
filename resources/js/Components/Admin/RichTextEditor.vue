<script setup>
import { ref, watch, onMounted } from 'vue';
import { Bold, Italic, Underline, List, ListOrdered, Strikethrough } from 'lucide-vue-next';

const props = defineProps({
  modelValue: { type: String, default: '' },
  placeholder: { type: String, default: 'Write here...' },
  minHeight: { type: String, default: '160px' },
});

const emit = defineEmits(['update:modelValue']);

const editorEl = ref(null);
const isFocused = ref(false);

function exec(cmd, value = null) {
  editorEl.value?.focus();
  document.execCommand(cmd, false, value);
  sync();
}

function getSelectionText() {
  const sel = window.getSelection();
  if (!sel || sel.rangeCount === 0) return '';
  return sel.toString();
}

function insertList(tagName) {
  const el = editorEl.value;
  if (!el) return;
  el.focus();
  const listTag = tagName === 'ul' ? 'ul' : 'ol';
  const text = getSelectionText() || 'List item';
  const safe = String(text).replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&/g, '&amp;');
  const html = `<${listTag}><li>${safe}</li></${listTag}>`;
  document.execCommand('insertHTML', false, html);
  sync();
}

function sync() {
  if (!editorEl.value) return;
  const html = editorEl.value.innerHTML;
  if (html !== props.modelValue) emit('update:modelValue', html === '<br>' ? '' : html);
}

function onInput() {
  sync();
}

function onPaste(e) {
  e.preventDefault();
  const text = e.clipboardData?.getData('text/plain') || '';
  document.execCommand('insertText', false, text);
}

onMounted(() => {
  if (editorEl.value) {
    const val = props.modelValue || '';
    editorEl.value.innerHTML = val || '';
    if (!val && editorEl.value.innerHTML === '') {
      editorEl.value.innerHTML = '';
    }
  }
});

watch(
  () => props.modelValue,
  (val) => {
    if (!editorEl.value) return;
    if (editorEl.value.innerHTML !== val) {
      editorEl.value.innerHTML = val || '';
    }
  },
  { immediate: true }
);
</script>

<template>
  <div
    class="rounded-xl border border-zinc-300 bg-white dark:border-zinc-600 dark:bg-zinc-800"
    :class="{ 'ring-2 ring-amber-500': isFocused }"
  >
    <div class="flex flex-wrap gap-1 border-b border-zinc-200 px-2 py-1.5 dark:border-zinc-600">
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Bold" @click="exec('bold')">
        <Bold class="h-4 w-4" stroke-width="2" />
      </button>
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Italic" @click="exec('italic')">
        <Italic class="h-4 w-4" stroke-width="2" />
      </button>
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Underline" @click="exec('underline')">
        <Underline class="h-4 w-4" stroke-width="2" />
      </button>
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Strikethrough" @click="exec('strikeThrough')">
        <Strikethrough class="h-4 w-4" stroke-width="2" />
      </button>
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Bullet list" @click="insertList('ul')">
        <List class="h-4 w-4" stroke-width="2" />
      </button>
      <button type="button" class="rounded p-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700" title="Numbered list" @click="insertList('ol')">
        <ListOrdered class="h-4 w-4" stroke-width="2" />
      </button>
    </div>
    <div
      ref="editorEl"
      contenteditable="true"
      class="prose prose-sm dark:prose-invert max-w-none overflow-auto px-4 py-3 text-zinc-900 focus:outline-none dark:text-zinc-100"
      :style="{ minHeight }"
      :data-placeholder="placeholder"
      role="textbox"
      aria-multiline="true"
      @input="onInput"
      @focus="isFocused = true"
      @blur="isFocused = false; sync()"
      @paste="onPaste"
    />
  </div>
</template>

<style scoped>
[contenteditable][data-placeholder]:empty::before {
  content: attr(data-placeholder);
  color: #a1a1aa;
}
.dark [contenteditable][data-placeholder]:empty::before {
  color: #71717a;
}
</style>
