<script setup>
import { computed } from 'vue';
import { Package, MapPin, Truck } from 'lucide-vue-next';

const defaultPillars = [
  { icon: Package, title: 'Curated selection', text: 'Focused picks across watches, perfumes, and serums — chosen for quality and premium presentation.' },
  { icon: MapPin, title: 'Concierge support', text: 'Quick help via WhatsApp — sizes, scent profiles, routine tips, and order updates.' },
  { icon: Truck, title: 'Careful delivery', text: 'Careful packing and a reliable delivery flow so your order arrives protected.' },
];
const icons = [Package, MapPin, Truck];
const props = defineProps({ section: { type: Object, default: null } });
const heading = computed(() => props.section?.heading || 'Curated luxury essentials, delivered with care.');
const pillars = computed(() => {
  const raw = props.section?.pillars;
  if (!Array.isArray(raw) || raw.length < 3) return defaultPillars;
  return raw.slice(0, 3).map((p, i) => ({
    icon: icons[i] || Package,
    title: p?.title || defaultPillars[i]?.title || '',
    text: p?.text || defaultPillars[i]?.text || '',
  }));
});
</script>

<template>
  <section class="why-pillars relative overflow-hidden bg-luxe-obsidian" aria-label="Why us">
    <div class="pointer-events-none absolute inset-0 bg-luxe-radial opacity-70" aria-hidden="true" />
    <div class="absolute left-0 right-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent" aria-hidden="true" />

    <div class="luxe-container relative py-14 sm:py-24 lg:py-28">
      <div class="text-center">
        <p class="luxe-kicker">Why choose us</p>
        <h2 class="luxe-title mx-auto mt-4 max-w-4xl text-2xl font-semibold tracking-tight sm:text-3xl md:text-4xl lg:text-[2.5rem]">
          {{ heading }}
        </h2>
        <div class="mx-auto mt-6 h-px w-24 bg-gradient-to-r from-transparent via-luxe-gold/70 to-transparent" aria-hidden="true" />
      </div>

      <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3 lg:gap-6">
        <div
          v-for="(p, i) in pillars"
          :key="i"
          class="pillar group luxe-surface relative flex flex-col overflow-hidden rounded-3xl p-5 transition-all duration-300 hover:bg-white/10 sm:p-7 md:p-8"
        >
          <span class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-luxe-gold/10 blur-2xl transition-opacity duration-300 group-hover:opacity-100 opacity-70" aria-hidden="true" />
          <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-black/30 text-luxe-pearl transition-colors duration-300 group-hover:border-luxe-gold/30">
            <component :is="p.icon" class="h-7 w-7" stroke-width="1.5" />
          </div>
          <h3 class="relative mt-6 font-display text-xl font-semibold tracking-tight text-luxe-pearl">
            {{ p.title }}
          </h3>
          <p class="relative mt-3 flex-1 text-base leading-relaxed text-luxe-pearl/80">
            {{ p.text }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.why-pillars {
  isolation: isolate;
}
</style>
