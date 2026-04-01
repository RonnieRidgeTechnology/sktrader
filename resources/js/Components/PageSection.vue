<script setup>
import { computed } from 'vue';
/** Premium section wrapper: optional vector, title, subtitle, background pattern. */
const props = defineProps({
  theme: { type: String, default: 'editorial' }, // editorial = concept styling (cream/ink/coral)
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  variant: { type: String, default: 'default' }, // default | muted | accent
  containerClass: { type: String, default: '' },
  backgroundPattern: { type: String, default: '' }, // 'steps' = dot grid for How It Works, etc.
});
const isEditorial = computed(() => props.theme !== 'classic');

const stepsPatternStyle = {
  backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'8\' cy=\'8\' r=\'1.2\' fill=\'%23a1a1aa\'/%3E%3Ccircle cx=\'24\' cy=\'8\' r=\'1.2\' fill=\'%23a1a1aa\'/%3E%3Ccircle cx=\'16\' cy=\'20\' r=\'1.2\' fill=\'%23a1a1aa\'/%3E%3Ccircle cx=\'8\' cy=\'32\' r=\'1.2\' fill=\'%23a1a1aa\'/%3E%3Ccircle cx=\'24\' cy=\'32\' r=\'1.2\' fill=\'%23a1a1aa\'/%3E%3C/svg%3E")',
  backgroundSize: '40px 40px',
};
const stepsPatternEditorial = {
  backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 12h24M12 0v24\' stroke=\'%2344403c\' stroke-width=\'0.5\' opacity=\'0.2\' fill=\'none\'/%3E%3C/svg%3E")',
  backgroundSize: '24px 24px',
};
</script>

<template>
  <section
    class="relative min-w-0 overflow-hidden px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-24"
    :class="[
      isEditorial
        ? (variant === 'muted' ? 'bg-editorial-paper border-y-2 border-editorial-ink/10' : 'bg-editorial-cream')
        : {
            'bg-white dark:bg-zinc-900': variant === 'default',
            'bg-zinc-50 dark:bg-zinc-900/60 border-y border-zinc-200/80 dark:border-zinc-800': variant === 'muted',
            'bg-gradient-to-b from-amber-50/50 to-white dark:from-zinc-900 dark:to-zinc-900': variant === 'accent',
          },
    ]"
  >
    <!-- Background pattern -->
    <div
      v-if="backgroundPattern === 'steps' && !isEditorial"
      class="pointer-events-none absolute inset-0 opacity-[0.45] dark:opacity-[0.2]"
      aria-hidden="true"
      :style="stepsPatternStyle"
    />
    <div v-if="backgroundPattern === 'steps' && isEditorial" class="pointer-events-none absolute inset-0 opacity-30" aria-hidden="true" :style="stepsPatternEditorial" />
    <div class="relative mx-auto min-w-0 max-w-7xl" :class="containerClass">
      <template v-if="$slots.vector">
        <div class="grid gap-8 lg:grid-cols-12 lg:gap-16 lg:items-stretch">
          <div class="order-2 flex min-w-0 justify-center lg:order-1 lg:col-span-5 lg:min-h-0 xl:col-span-4">
            <div class="w-full max-w-[260px] sm:max-w-[280px] lg:max-w-none lg:min-h-[280px] lg:h-full aspect-square lg:aspect-auto" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-400 dark:text-zinc-500'">
              <slot name="vector" />
            </div>
          </div>
          <div class="order-1 min-w-0 lg:order-2 lg:col-span-7 xl:col-span-8">
            <h2 v-if="title" class="font-bold tracking-tight sm:text-3xl lg:text-4xl" :class="isEditorial ? 'font-editorial text-2xl text-editorial-ink' : 'text-2xl text-zinc-900 dark:text-white'">
              {{ title }}
            </h2>
            <p v-if="subtitle" class="mt-3 max-w-2xl text-base sm:text-lg" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">
              {{ subtitle }}
            </p>
            <div class="mt-8">
              <slot />
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <h2 v-if="title" class="text-2xl font-bold tracking-tight sm:text-3xl lg:text-4xl" :class="isEditorial ? 'font-editorial text-center text-editorial-ink' : 'text-center text-zinc-900 dark:text-white'">
          {{ title }}
        </h2>
        <p v-if="subtitle" class="mx-auto mt-3 max-w-2xl text-center text-base sm:text-lg" :class="isEditorial ? 'text-editorial-slate' : 'text-zinc-600 dark:text-zinc-400'">
          {{ subtitle }}
        </p>
        <div class="mt-10 lg:mt-12">
          <slot />
        </div>
      </template>
    </div>
  </section>
</template>
