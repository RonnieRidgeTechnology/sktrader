<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Quote } from 'lucide-vue-next';

const AUTO_SLIDE_MS = 4000;

const props = defineProps({
  testimonials: { type: Array, default: () => [] },
});

const list = computed(() => (Array.isArray(props.testimonials) ? props.testimonials : []).filter(Boolean));
const current = ref(0);
const total = computed(() => list.value.length);
let timer = null;

function next() {
  if (total.value <= 1) return;
  current.value = (current.value + 1) % total.value;
  startTimer();
}
function goTo(i) {
  current.value = i;
  startTimer();
}
function startTimer() {
  if (timer) clearInterval(timer);
  timer = null;
  if (total.value > 1) timer = setInterval(next, AUTO_SLIDE_MS);
}
function stopTimer() {
  if (timer) clearInterval(timer);
  timer = null;
}

watch(total, (n) => {
  if (n > 1) startTimer();
  else stopTimer();
  if (current.value >= n && n > 0) current.value = 0;
}, { immediate: true });

onMounted(() => startTimer());
onUnmounted(stopTimer);
</script>

<template>
  <section class="quote-block bg-luxe-obsidian" aria-label="Testimonials">
    <div class="luxe-container py-20 sm:py-28">
      <div class="mx-auto max-w-4xl text-center">
      <p class="luxe-kicker">Testimonials</p>
      <template v-if="list.length">
        <div class="relative mt-10 min-h-[280px] sm:min-h-[320px]">
          <blockquote
            v-for="(item, i) in list"
            :key="i"
            class="absolute inset-0 flex flex-col items-center justify-center px-4 transition-opacity duration-500"
            :class="i === current ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
          >
            <div class="luxe-surface-strong w-full rounded-4xl p-8 sm:p-10">
              <Quote class="mx-auto mb-6 h-12 w-12 text-luxe-gold/60 sm:h-14 sm:w-14" aria-hidden="true" />
              <p class="font-display text-2xl font-medium leading-relaxed text-luxe-pearl sm:text-3xl md:text-4xl">
              "{{ item.quote }}"
              </p>
              <footer class="mt-8 flex flex-col items-center gap-1">
                <span class="font-semibold text-luxe-pearl">{{ item.name }}</span>
                <span v-if="item.role || item.company" class="text-sm text-luxe-mist/85">
                  {{ [item.role, item.company].filter(Boolean).join(' · ') }}
                </span>
              </footer>
            </div>
          </blockquote>
        </div>
        <div v-if="total > 1" class="mt-10 flex justify-center gap-3">
          <button
            v-for="(_, i) in list"
            :key="i"
            type="button"
            :aria-label="`Quote ${i + 1}`"
            :aria-current="i === current ? 'true' : undefined"
            class="h-2 w-8 transition-colors duration-300"
            :class="i === current ? 'bg-luxe-gold' : 'bg-white/15 hover:bg-white/25'"
            @click="goTo(i)"
          />
        </div>
      </template>
      <div v-else class="mt-10 flex min-h-[200px] flex-col items-center justify-center text-luxe-mist/80">
        <Quote class="mb-4 h-12 w-12" aria-hidden="true" />
        <p>Testimonials will appear here.</p>
      </div>
    </div>
    </div>
  </section>
</template>
