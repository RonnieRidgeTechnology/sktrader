<script setup>
import { ref, computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import { useJsonLd } from '@/composables/useJsonLd';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageSection from '@/Components/PageSection.vue';
import VectorFaq from '@/Components/Vectors/VectorFaq.vue';

const defaultFaqs = [
  { question: 'Do you deliver across Zambia?', answer: 'Yes. We offer nationwide shipping across Zambia. Whether you are in Lusaka, Ndola, Kitwe, Livingstone or elsewhere, we can deliver your furniture safely. Delivery terms and any costs depend on your location and order size—we will confirm this when you order or enquire.' },
  { question: 'Can I visit your showroom in Lusaka?', answer: 'Yes. Our showroom in Lusaka is open for you to view sofas, garden furniture and other pieces in person. You can try before you buy and get advice from our team. We recommend calling or messaging ahead to confirm opening hours.' },
  { question: 'What payment methods do you accept?', answer: 'We accept cash, bank transfer and other methods as agreed. For your convenience, we offer pay on delivery (COD) options where applicable. Exact payment options will be confirmed when you place an order or contact us.' },
  { question: 'Do you offer warranty on your furniture?', answer: 'Yes. We stand by our quality with warranty on our furniture as specified per product. If you need help after your purchase, our after-sales support is here to assist you.' },
  { question: 'How long does delivery take?', answer: 'Delivery times vary by product availability and your location. In Lusaka, delivery is typically within a few days to a week. For other parts of Zambia, it may take a bit longer. We will give you an estimated timeframe when you order.' },
  { question: 'What types of furniture do you sell?', answer: 'We sell a wide range of furniture including sofas and living room furniture, rattan and garden furniture (UV-resistant outdoor sets, corner sofas, chairs, tables), and other quality pieces for home and garden. From classic to modern styles—there is something for every space.' },
  { question: 'How can I arrange a viewing or collection?', answer: 'You can message us on WhatsApp to arrange a viewing at our showroom or to organise collection. Use the WhatsApp button on our website or product pages—we are happy to help you find the right furniture and arrange a convenient time.' },
  { question: 'Can I order custom or made-to-order furniture?', answer: 'We offer selected custom and made-to-order options. Contact us with your requirements—fabric, size, style—and we will let you know what is possible and provide a quote.' },
  { question: 'How do I care for rattan or outdoor garden furniture?', answer: 'Our UV-resistant rattan and outdoor furniture is built for durability. For best results, keep pieces clean with a soft cloth and mild soap, and avoid leaving them in standing water. We can provide care guidelines with your purchase.' },
  { question: 'Do you have furniture in stock, and how do I check availability?', answer: 'Availability varies by product. You can browse our website for current offerings, visit our Lusaka showroom to see and try pieces, or contact us via WhatsApp or phone to check stock for specific items. We will confirm availability when you enquire or order.' },
];

const props = defineProps({
  title: { type: String, default: 'FAQ' },
  faqs: { type: Array, default: () => [] },
  pageContent: { type: Object, default: () => ({}) },
});
const pc = computed(() => props.pageContent || {});
const pageTitle = computed(() => pc.value.hero_title || 'Frequently Asked Questions');
const pageSubtitle = computed(() => pc.value.hero_subtitle || 'Common questions about our furniture, delivery and services.');

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
  title: 'FAQ | Atlantic Garden Furniture',
  description: 'Frequently asked questions about our furniture, delivery in Zambia, payment and warranty.',
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
            class="border-2 border-editorial-ink/15 bg-white transition hover:border-editorial-coral/40"
          >
            <button
              type="button"
              class="flex min-h-[48px] w-full min-w-0 items-center justify-between gap-3 px-4 py-4 text-left font-medium text-editorial-ink sm:px-6"
              :aria-expanded="openIndex === index"
              @click="openIndex = openIndex === index ? null : index"
            >
              <span class="min-w-0 flex-1 break-words text-left">{{ faq.question }}</span>
              <span class="shrink-0 text-editorial-coral">{{ openIndex === index ? '−' : '+' }}</span>
            </button>
            <div
              v-show="openIndex === index"
              class="border-t border-editorial-ink/10 px-4 py-4 text-editorial-slate sm:px-6"
            >
              {{ faq.answer }}
            </div>
          </div>
        </template>
      </dl>
    </PageSection>
  </AppLayout>
</template>
