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
    <SeoHead title="Thank You | SK Traders" :description="message.slice(0, 160)" :noindex="true" />
    <section class="bg-luxe-obsidian">
      <div class="luxe-container py-20 sm:py-24">
        <div class="luxe-surface rounded-4xl p-8 text-center sm:p-10">
          <p class="luxe-kicker">Message received</p>
          <h1 class="luxe-title mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">{{ heading }}</h1>
          <p class="mt-4 text-base text-luxe-pearl/80 sm:text-lg">
            {{ message }}
          </p>
          <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <Link :href="route('home')" class="luxe-btn luxe-btn-primary">
              Back to home
            </Link>
            <Link :href="route('products')" class="luxe-btn luxe-btn-ghost">
              Continue shopping
            </Link>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
