<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ title: { type: String, default: 'Thank You' }, pageContent: { type: Object, default: () => ({}) } });
const pc = computed(() => props.pageContent || {});
const heading = computed(() => pc.value.title || 'Thank you for your inquiry');
const message = computed(() => pc.value.message || 'We have received your message and will respond within 24 hours.');
</script>

<template>
  <AppLayout>
    <SeoHead title="Thank You | Atlantic Garden Furniture" :description="message.slice(0, 160)" :noindex="true" />
    <div class="mx-auto max-w-2xl bg-editorial-cream px-4 py-24 text-center sm:px-6 lg:px-8">
      <h1 class="font-editorial text-3xl font-bold tracking-tight text-editorial-ink sm:text-4xl">{{ heading }}</h1>
      <p class="mt-4 text-lg text-editorial-slate">
        {{ message }}
      </p>
      <Link
        :href="route('home')"
        class="mt-8 inline-flex border-2 border-editorial-coral bg-editorial-coral px-6 py-3.5 text-base font-semibold text-white transition hover:bg-[#a83609] hover:border-[#a83609]"
      >
        Back to Home
      </Link>
    </div>
  </AppLayout>
</template>
