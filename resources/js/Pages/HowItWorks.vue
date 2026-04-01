<script setup>
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';
import HowItWorksSteps from '@/Components/HowItWorksSteps.vue';
import CtaBanner from '@/Components/CtaBanner.vue';

const props = defineProps({ title: { type: String, default: 'How It Works' }, pageContent: Object });
const pc = props.pageContent || {};

const pageSeoProps = usePageSeo(null, {
  title: 'How It Works | SK Traders',
  description: 'From discovery to delivery: browse, checkout, and receive curated watches, perfumes, and serums with care.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection
      variant="muted"
      background-pattern="steps"
      :title="pc.hero_title || 'How It Works'"
      :subtitle="pc.hero_subtitle || 'From discovery to delivery — simple steps to get what you want, fast.'"
    >
      <HowItWorksSteps theme="luxe" />
    </PageSection>
    <CtaBanner />
  </AppLayout>
</template>
