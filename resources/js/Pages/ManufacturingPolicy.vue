<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';

const props = defineProps({ title: { type: String, default: 'Manufacturing Policy' }, pageContent: { type: Object, default: () => ({}) } });
const pc = computed(() => props.pageContent || {});
const pageTitle = computed(() => pc.value.title || 'Manufacturing Policy');
const content = computed(() => (pc.value.content || '').trim());

const pageSeoProps = usePageSeo(null, {
  title: 'Delivery & Returns | Atlantic Garden Furniture',
  description: content.value ? String(content.value).slice(0, 160) : 'Delivery and returns policy.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection :title="pageTitle" :subtitle="content ? '' : 'Our commitment to quality, timelines, and transparent processes.'">
      <div
        v-if="content"
        class="prose prose-zinc max-w-none space-y-6 text-editorial-slate prose-p:leading-relaxed prose-a:text-editorial-coral prose-a:underline"
        v-html="content"
      />
      <div v-else class="prose prose-zinc max-w-none space-y-6 text-editorial-slate">
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Quality standards</h2>
          <p>We maintain quality at every stage: pre-production sampling, in-process inspection, and final QC. Products must meet agreed specifications before shipment.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Lead times</h2>
          <p>Production lead times vary by product and order size, typically 10–25 working days after sample approval and deposit. We communicate timelines clearly at quote stage.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Minimum order quantity (MOQ)</h2>
          <p>MOQs depend on product type. We offer low MOQs where possible to support startups and small brands. Specific MOQs are provided with each quote.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Samples & approval</h2>
          <p>We produce samples for approval before bulk production. Bulk production begins only after your written approval. Modifications may affect lead time and cost.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Contact</h2>
          <p>For manufacturing or production questions, reach us via the <Link :href="route('contact')" class="text-editorial-coral underline hover:no-underline">Contact</Link> page or email.</p>
        </section>
      </div>
    </PageSection>
  </AppLayout>
</template>
