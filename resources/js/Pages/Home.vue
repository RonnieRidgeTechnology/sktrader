<script setup>
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import RoomHero from '@/Components/Home/RoomHero.vue';
import CategoriesShowcase from '@/Components/Home/CategoriesShowcase.vue';
import WhyPillars from '@/Components/Home/WhyPillars.vue';
import ReelsStrip from '@/Components/Home/ReelsStrip.vue';
import QuoteBlock from '@/Components/Home/QuoteBlock.vue';
import DeliveryLine from '@/Components/Home/DeliveryLine.vue';
import FinalCta from '@/Components/Home/FinalCta.vue';
import FeaturedProductsCarousel from '@/Components/FeaturedProductsCarousel.vue';
import { computed } from 'vue';

const page = usePage();
const heroHeadline = page.props.heroHeadline ?? 'Luxury essentials, curated.';
const heroSubheading = page.props.heroSubheading ?? 'Watches, perfumes, and skin care serums — premium picks, delivered with care.';
const heroSliderImages = page.props.heroSliderImages ?? [];
const featuredProducts = page.props.featuredProducts ?? [];
const videoReels = page.props.videoReels ?? [];
const testimonials = page.props.testimonials ?? [];
const zuaaz = page.props.zuaaz || {};
const homeSections = page.props.homeSections || {};
const whatsappPrimary = () => {
  const num = (zuaaz.whatsapp?.primary || '+260970000000').replace(/\D/g, '');
  return `https://wa.me/${num}`;
};

const navCategories = computed(() => page.props.navCategories || []);
function normalizeName(s) {
  return String(s || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
}
const luxeCategoryGateways = computed(() => {
  const list = navCategories.value || [];
  const parents = list.filter((c) => !c.parent_id);
  const pick = (needle) => parents.find((c) => normalizeName(c.name).includes(needle));
  const watches = pick('watch');
  const perfumes = pick('perfume') || pick('fragrance');
  const serums = pick('serum') || pick('skincare') || pick('skin');
  const chosen = [watches, perfumes, serums].filter(Boolean);
  const rest = parents.filter((p) => !chosen.some((c) => c.id === p.id));
  const final = [...chosen, ...rest].slice(0, 3);
  return final.map((c) => ({ label: c.name, slug: c.slug }));
});

const homeCategoryShowcase = computed(() => {
  const list = navCategories.value || [];
  const parents = list.filter((c) => !c.parent_id);
  return parents.slice(0, 8).map((c) => ({ label: c.name, slug: c.slug }));
});

const defaultRegions = [
  { name: 'Lusaka' },
  { name: 'Copperbelt' },
  { name: 'Southern' },
  { name: 'Central' },
  { name: 'Northern' },
  { name: 'Nationwide' },
];
const regions = (homeSections.delivery_line?.regions || []).length
  ? homeSections.delivery_line.regions.map((name) => ({ name: String(name) }))
  : defaultRegions;

const pageSeoProps = usePageSeo(null, {
  title: 'Watches, Perfumes & Skin Care Serums | SK Traders',
  description: 'Shop curated watches, perfumes, and skin care serums at SK Traders. Premium picks, careful packing, and concierge-style support.',
  keywords: 'watches, perfumes, skin care serums, skincare, fragrance, SK Traders',
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <div class="home-luxe">
      <!-- 1. Luxe hero -->
      <RoomHero
        :whatsapp-link="whatsappPrimary()"
        :headline="heroHeadline || 'Luxury essentials, curated.'"
        :subheading="heroSubheading || 'Watches, perfumes, and skincare serums — premium picks, delivered with care.'"
        :slider-images="heroSliderImages"
        :categories="luxeCategoryGateways"
      />

      <!-- Categories (modern) -->
      <CategoriesShowcase :categories="homeCategoryShowcase" />

      <!-- Featured products carousel (auto-slide, 4 visible on desktop, shifts 1 at a time) -->
      <FeaturedProductsCarousel
        v-if="featuredProducts.length > 0"
        theme="luxe"
        :products="featuredProducts"
        eyebrow="Featured"
        title="Featured products"
        subtitle="Auto-sliding highlights — 4 at a time, shifting one-by-one."
      />

      <!-- 2. Why us – three pillars -->
      <WhyPillars :section="homeSections.why_pillars" />

      <!-- 3. Reels – horizontal strip -->
      <ReelsStrip :videos="videoReels" />

      <!-- 4. One big quote -->
      <QuoteBlock :testimonials="testimonials" />

      <!-- 5. Delivery line -->
      <DeliveryLine :regions="regions" :section="homeSections.delivery_line" />

      <!-- 6. Final CTA -->
      <FinalCta :section="homeSections.final_cta" />
    </div>
  </AppLayout>
</template>

<style scoped>
.home-luxe :deep(.revealed) {
  animation: reveal-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
</style>
