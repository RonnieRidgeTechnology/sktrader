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
  title: 'How It Works | Atlantic Garden Furniture',
  description: 'From browsing to delivery: visit our showroom or order online. We deliver sofas and furniture across Zambia.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection
      variant="muted"
      background-pattern="steps"
      :title="pc.hero_title || 'How It Works'"
      :subtitle="pc.hero_subtitle || 'From browsing to delivery — simple steps to get the furniture you want.'"
    >
      <HowItWorksSteps theme="editorial" />
    </PageSection>
    <CtaBanner />
  </AppLayout>
</template>
