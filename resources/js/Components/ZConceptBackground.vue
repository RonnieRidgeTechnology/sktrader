<script setup>
/**
 * Z Concept background – stylized angular Z from Atlantic garden Furniture logo.
 * Sharp diagonals, interlocking/layered. Use as subtle watermark in sections.
 */
import { computed, ref } from 'vue';

const props = defineProps({
  size: { type: String, default: 'large' }, // large | medium | small
  opacity: { type: [String, Number], default: 0.06 },
  pattern: { type: Boolean, default: false },
  variant: { type: String, default: 'default' }, // default (dark Z) | light (white Z for dark sections)
});

const opacityStyle = computed(() => ({ opacity: Number(props.opacity) || 0.06 }));
const colorClass = computed(() => (props.variant === 'light' ? 'text-white' : 'text-zinc-900 dark:text-zinc-100'));
let idSeq = 0;
const patternId = ref('z-concept-bg-' + ++idSeq);
</script>

<template>
  <div
    class="pointer-events-none absolute inset-0 overflow-hidden"
    aria-hidden="true"
  >
    <template v-if="!pattern">
      <svg
        class="absolute left-1/2 top-1/2 h-[140%] w-[140%] -translate-x-1/2 -translate-y-1/2 select-none"
        :class="{
          'scale-75': size === 'medium',
          'scale-50': size === 'small',
        }"
        viewBox="0 0 200 200"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g :style="opacityStyle" :class="colorClass">
          <!-- Angular Z: top bar, diagonal, bottom bar (logo-style) -->
          <path d="M30 50 L170 50 L170 65 L55 135 L55 150 L170 150" stroke="currentColor" stroke-width="8" stroke-linecap="square" stroke-linejoin="miter" fill="none" />
          <path d="M45 65 L185 65 L185 80 L70 150 L70 165 L185 165" stroke="currentColor" stroke-width="5" stroke-linecap="square" fill="none" />
        </g>
      </svg>
    </template>
    <template v-else>
      <svg class="absolute inset-0 h-full w-full select-none" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern :id="patternId" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
            <path d="M10 20 L110 20 L110 28 L32 92 L32 100 L120 100" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" fill="none" :style="opacityStyle" :class="colorClass" />
          </pattern>
        </defs>
        <rect width="100%" height="100%" :fill="'url(#' + patternId + ')'" />
      </svg>
    </template>
  </div>
</template>
