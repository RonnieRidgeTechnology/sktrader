<script setup>
import { ref, computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import { useJsonLd } from '@/composables/useJsonLd';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';
import VectorFaq from '@/Components/Vectors/VectorFaq.vue';

const defaultFaqs = [
  { question: 'Do you deliver across Zambia?', answer: 'Yes. We offer delivery across Zambia. Delivery timelines and any costs depend on your location and the items in your order—we will confirm this during checkout or when you enquire.' },
  { question: 'How can I get help choosing a product?', answer: 'Message us on WhatsApp using the button on the website. Tell us what you’re looking for (watch / perfume / serum), your budget range, and preferences (style, scent profile, or skin routine goals). We’ll recommend options quickly.' },
  { question: 'What payment methods do you accept?', answer: 'We accept cash, bank transfer and other methods as agreed. For your convenience, we offer pay on delivery (COD) options where applicable. Exact payment options will be confirmed when you place an order or contact us.' },
  { question: 'Do you offer warranty or support?', answer: 'Support depends on the product category. If you need help after your purchase, contact us and we’ll assist you based on the item and order details.' },
  { question: 'How long does delivery take?', answer: 'Delivery times vary by product availability and your location. We’ll provide an estimated timeframe when you order or when you enquire.' },
  { question: 'What products do you sell?', answer: 'We curate watches, perfumes, and skin care serums. Availability changes as we add new drops and restock popular items.' },
  { question: 'Can I order via WhatsApp?', answer: 'Yes. You can chat with us on WhatsApp to confirm availability, ask questions, and place an order if needed.' },
  { question: 'Do you offer gifting or curated bundles?', answer: 'Yes. We can help you choose gift-ready picks or create curated bundles (for example: perfume + serum, or a watch + fragrance). Message us and share the occasion and budget.' },
  { question: 'How do I choose a perfume scent profile?', answer: 'Tell us the vibe you want (fresh, sweet, woody, spicy) and when you’ll wear it (daily, office, evening). We’ll recommend options that match.' },
  { question: 'How do I check availability?', answer: 'Availability varies by product. You can browse our website for current offerings or contact us via WhatsApp to confirm stock for a specific item.' },
];

const props = defineProps({
  title: { type: String, default: 'FAQ' },
  faqs: { type: Array, default: () => [] },
  pageContent: { type: Object, default: () => ({}) },
});
const pc = computed(() => props.pageContent || {});
const pageTitle = computed(() => pc.value.hero_title || 'Frequently Asked Questions');
const pageSubtitle = computed(() => pc.value.hero_subtitle || 'Common questions about ordering, delivery, and support.');

const openIndex = ref(null);
const list = computed(() => (props.faqs && props.faqs.length ? props.faqs : defaultFaqs));

useJsonLd(() => ({
  '@context': 'https://schema.org',
  '@type': 'FAQPage',
  mainEntity: list.value.map((faq) => ({
    '@type': 'Question',
    name: faq.question,
    acceptedAnswer: { '@type': 'Answer', text: faq.answer },
  })),
}));

const pageSeoProps = usePageSeo(null, {
  title: 'FAQ | SK Traders',
  description: 'Frequently asked questions about ordering watches, perfumes, and serums — delivery, payment, and support.',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />
    <PageSection
      :title="pageTitle"
      :subtitle="pageSubtitle"
    >
      <template #vector>
        <VectorFaq />
      </template>
      <dl class="space-y-4">
        <template v-for="(faq, index) in list" :key="index">
          <div
            class="luxe-surface rounded-3xl p-1 transition hover:bg-white/10"
          >
            <button
              type="button"
              class="flex min-h-[56px] w-full min-w-0 items-center justify-between gap-3 rounded-[1.375rem] px-5 py-4 text-left font-semibold text-luxe-pearl sm:px-6"
              :aria-expanded="openIndex === index"
              @click="openIndex = openIndex === index ? null : index"
            >
              <span class="min-w-0 flex-1 break-words text-left">{{ faq.question }}</span>
              <span class="shrink-0 text-luxe-gold">{{ openIndex === index ? '−' : '+' }}</span>
            </button>
            <div
              v-show="openIndex === index"
              class="border-t border-white/10 px-5 py-4 text-luxe-pearl/80 sm:px-6"
            >
              {{ faq.answer }}
            </div>
          </div>
        </template>
      </dl>
    </PageSection>
  </AppLayout>
</template>
