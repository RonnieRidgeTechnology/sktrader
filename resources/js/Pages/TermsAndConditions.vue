<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';

const props = defineProps({ title: { type: String, default: 'Terms & Conditions' }, pageContent: { type: Object, default: () => ({}) } });
const pc = computed(() => props.pageContent || {});
const pageTitle = computed(() => pc.value.title || 'Terms & Conditions');
const content = computed(() => (pc.value.content || '').trim());

const pageSeoProps = usePageSeo(null, {
  title: 'Terms & Conditions | Atlantic Garden Furniture',
  description: content.value ? String(content.value).slice(0, 160) : 'Terms and conditions for purchasing furniture.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection :title="pageTitle" :subtitle="content ? '' : 'Terms governing our furniture sales and services.'">
      <div
        v-if="content"
        class="prose prose-zinc max-w-none space-y-6 text-editorial-slate prose-p:leading-relaxed prose-a:text-editorial-coral prose-a:underline"
        v-html="content"
      />
      <div v-else class="prose prose-zinc max-w-none space-y-6 text-editorial-slate">
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Scope</h2>
          <p>These terms apply to all purchases and enquiries made with Atlantic Garden Furniture. By placing an order or sending an enquiry, you agree to these terms unless otherwise agreed in writing.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Quotes & orders</h2>
          <p>Quotes are valid for the period stated. Orders are confirmed upon written acceptance and, where required, deposit payment. Specifications and quantities must be clearly agreed before production.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Payment</h2>
          <p>Payment terms (e.g. deposit and balance) are as agreed per order. We accept bank transfer and other methods as communicated. Title and risk pass as per Incoterms or as agreed.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Cancellation & changes</h2>
          <p>Cancellation or significant changes after production has started may incur charges. We will communicate any impact before proceeding.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Quality & claims</h2>
          <p>Claims must be reported within the period agreed or a reasonable time after receipt. We will work with you to resolve legitimate quality issues in line with our manufacturing policy.</p>
        </section>
        <section>
          <h2 class="font-editorial text-lg font-semibold text-editorial-ink">Contact</h2>
          <p>For questions about these terms, contact us via the <Link :href="route('contact')" class="text-editorial-coral underline hover:no-underline">Contact</Link> page.</p>
        </section>
      </div>
    </PageSection>
  </AppLayout>
</template>
