<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { ChevronDown, Search } from 'lucide-vue-next';

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: 'Select...' },
  searchPlaceholder: { type: String, default: 'Search...' },
  label: { type: String, default: '' },
  required: { type: Boolean, default: false },
  /** Option shape: { id, name } or { value, label }. We use id/name by default. */
  valueKey: { type: String, default: 'id' },
  labelKey: { type: String, default: 'name' },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const searchQuery = ref('');
const triggerRef = ref(null);
const dropdownRef = ref(null);

const selectedOption = computed(() => {
  const v = props.modelValue;
  if (v === '' || v === null || v === undefined) return null;
  return props.options.find((o) => (o[props.valueKey] ?? o.id) == v) ?? null;
});

const selectedLabel = computed(() => {
  const o = selectedOption.value;
  return o ? (o[props.labelKey] ?? o.name) : '';
});

const filteredOptions = computed(() => {
  const q = searchQuery.value.trim().toLowerCase();
  if (!q) return props.options;
  return props.options.filter((o) => {
    const label = (o[props.labelKey] ?? o.name) ?? '';
    return String(label).toLowerCase().includes(q);
  });
});

function select(option) {
  const val = option[props.valueKey] ?? option.id;
  emit('update:modelValue', val);
  open.value = false;
  searchQuery.value = '';
}

function onOutsideClick(e) {
  if (!open.value) return;
  const tr = triggerRef.value;
  const dr = dropdownRef.value;
  if (!tr?.contains(e.target) && !dr?.contains(e.target)) open.value = false;
}

function onKeydown(e) {
  if (!open.value) return;
  if (e.key === 'Escape') {
    open.value = false;
    searchQuery.value = '';
  }
}

watch(open, (isOpen) => {
  if (isOpen) searchQuery.value = '';
});

onMounted(() => {
  document.addEventListener('click', onOutsideClick);
  document.addEventListener('keydown', onKeydown);
});
onUnmounted(() => {
  document.removeEventListener('click', onOutsideClick);
  document.removeEventListener('keydown', onKeydown);
});
</script>

<template>
  <div class="relative">
    <label v-if="label" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ label }}</label>
    <div class="relative mt-2">
      <button
        ref="triggerRef"
        type="button"
        class="flex w-full items-center justify-between gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-left text-zinc-900 shadow-sm transition focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
        :class="{ 'ring-1 ring-amber-500 border-amber-500': open }"
        aria-haspopup="listbox"
        :aria-expanded="open"
        aria-label="Category selection"
        @click="open = !open"
      >
        <span :class="selectedLabel ? 'text-zinc-900 dark:text-white' : 'text-zinc-500 dark:text-zinc-400'">
          {{ selectedLabel || placeholder }}
        </span>
        <ChevronDown
          class="h-5 w-5 shrink-0 text-zinc-400 transition-transform dark:text-zinc-500"
          :class="{ 'rotate-180': open }"
          stroke-width="2"
        />
      </button>

      <div
        v-show="open"
        ref="dropdownRef"
        class="absolute left-0 right-0 z-50 mt-1 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
        role="listbox"
      >
        <div class="border-b border-zinc-200 p-2 dark:border-zinc-700">
          <div class="flex items-center gap-2 rounded-lg bg-zinc-100 px-3 py-2 dark:bg-zinc-700/50">
            <Search class="h-4 w-4 shrink-0 text-zinc-400" stroke-width="2" />
            <input
              v-model="searchQuery"
              type="text"
              class="min-w-0 flex-1 bg-transparent text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none dark:text-white dark:placeholder-zinc-500"
              :placeholder="searchPlaceholder"
              autocomplete="off"
              @click.stop
            />
          </div>
        </div>
        <div class="max-h-60 overflow-y-auto py-1">
          <button
            v-for="opt in filteredOptions"
            :key="opt[valueKey] ?? opt.id"
            type="button"
            role="option"
            class="flex w-full items-center px-4 py-2.5 text-left text-sm transition"
            :class="(opt[valueKey] ?? opt.id) == modelValue
              ? 'bg-amber-50 font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-200'
              : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700'"
            @click="select(opt)"
          >
            {{ opt[labelKey] ?? opt.name }}
          </button>
          <p
            v-if="filteredOptions.length === 0"
            class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400"
          >
            No matches
          </p>
        </div>
      </div>
    </div>
    <!-- Hidden input for form validation (required) -->
    <input
      v-if="required"
      type="text"
      :value="modelValue"
      required
      class="absolute opacity-0 pointer-events-none h-0 w-0"
      tabindex="-1"
      aria-hidden="true"
    />
  </div>
</template>
