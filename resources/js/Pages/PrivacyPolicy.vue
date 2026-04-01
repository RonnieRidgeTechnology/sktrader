<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';

const props = defineProps({ title: { type: String, default: 'Privacy Policy' }, pageContent: { type: Object, default: () => ({}) } });
const page = usePage();
const pc = computed(() => props.pageContent || {});
const pageTitle = computed(() => pc.value.title || 'Privacy Policy');
const content = computed(() => (pc.value.content || '').trim());
const email = page.props.zuaaz?.contact?.email || 'info@atlanticgardenfurniture.com';

const pageSeoProps = usePageSeo(null, {
  title: 'Privacy Policy | SK Traders',
  description: content.value ? String(content.value).slice(0, 160) : 'Privacy policy for SK Traders.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection :title="pageTitle" :subtitle="content ? '' : 'How we collect, use, and protect your information.'">
      <div
        v-if="content"
        class="prose prose-zinc max-w-none space-y-6 text-editorial-slate prose-p:leading-relaxed prose-a:text-editorial-coral prose-a:underline"
        v-html="content"
      />
      <div v-else class="prose prose-zinc max-w-none space-y-6 text-editorial-slate">
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Information we collect</h2>
          <p>We collect information you provide when contacting us (name, email, company, phone, inquiry details). We use this to respond to quotes and partnership requests.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">How we use it</h2>
          <p>Your information is used only to process enquiries, communicate about orders and services, and improve our offerings. We do not sell your data to third parties.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Data security</h2>
          <p>We take reasonable measures to protect your data. Communications may be stored securely for the duration of our business relationship and as required by law.</p>
        </section>
        <section>
          <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Contact</h2>
          <p>For privacy-related questions, contact us at <a :href="`mailto:${email}`" class="text-editorial-coral underline hover:no-underline">{{ email }}</a>.</p>
        </section>
      </div>
    </PageSection>
  </AppLayout>
</template>
