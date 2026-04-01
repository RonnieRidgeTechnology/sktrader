<script setup>
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import RoomHero from '@/Components/Home/RoomHero.vue';
import BentoGrid from '@/Components/Home/BentoGrid.vue';
import Spotlight from '@/Components/Home/Spotlight.vue';
import JourneyStrip from '@/Components/Home/JourneyStrip.vue';
import WhyPillars from '@/Components/Home/WhyPillars.vue';
import ReelsStrip from '@/Components/Home/ReelsStrip.vue';
import QuoteBlock from '@/Components/Home/QuoteBlock.vue';
import DeliveryLine from '@/Components/Home/DeliveryLine.vue';
import FinalCta from '@/Components/Home/FinalCta.vue';
import FeaturedProductsCarousel from '@/Components/FeaturedProductsCarousel.vue';
import { computed } from 'vue';

const page = usePage();
const heroHeadline = page.props.heroHeadline ?? 'Quality Sofas & Furniture for Every Home';
const heroSubheading = page.props.heroSubheading ?? 'Comfort, Style & Value in Zambia';
const heroSliderImages = page.props.heroSliderImages ?? [];
const heroGalleryImages = page.props.heroGalleryImages ?? [];
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

/** Fallback when only one featured product has a photo and hero has no images — large left frame shows a different sofa. */
const SPOTLIGHT_FALLBACK_SOFA =
  'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1400&q=80';

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

const spotlightProduct = featuredProducts.length > 0 ? featuredProducts[0] : null;
const spotlightSectionImage = homeSections.spotlight?.image_url;
const featuredImageUrls = featuredProducts.map((p) => p.image_url).filter(Boolean);

/** Large left image: section image, else 2nd featured product, else hero slider (another sofa look), then product images. */
const spotlightImages = (() => {
  if (spotlightSectionImage) {
    return [
      spotlightSectionImage,
      ...featuredImageUrls.filter((u) => u !== spotlightSectionImage).slice(0, 2),
    ].slice(0, 3);
  }
  if (featuredImageUrls.length >= 2) {
    const main = featuredImageUrls[1];
    const rest = featuredImageUrls.filter((u) => u !== main);
    return [main, rest[0] ?? main, rest[1] ?? rest[0] ?? main].slice(0, 3);
  }
  if (featuredImageUrls.length === 1 && heroSliderImages.length > 0) {
    return [
      heroSliderImages[0],
      featuredImageUrls[0],
      heroSliderImages[1] ?? featuredImageUrls[0],
    ].slice(0, 3);
  }
  if (featuredImageUrls.length === 1) {
    return [SPOTLIGHT_FALLBACK_SOFA, featuredImageUrls[0], featuredImageUrls[0]].slice(0, 3);
  }
  return featuredImageUrls.slice(0, 3);
})();
const bentoImages = [...(heroSliderImages || []), ...(heroGalleryImages || [])].filter(Boolean);
const tagline = zuaaz.tagline || 'Lusaka showroom · Nationwide delivery';

const pageSeoProps = usePageSeo(null, {
  title: 'Sofas & Furniture Store Zambia | SK Traders Lusaka',
  description: 'Quality sofas and furniture in Zambia. SK Traders offers living room sofas, armchairs and home furniture. Visit us in Lusaka or order nationwide.',
  keywords: 'sofas Zambia, furniture store Lusaka, living room furniture, SK Traders, furniture delivery Zambia',
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

      <!-- Trust strip -->
      <section class="border-y border-white/10 bg-black/40">
        <div class="luxe-container py-10">
          <div class="grid gap-4 sm:grid-cols-3">
            <div class="luxe-surface rounded-3xl p-6">
              <p class="luxe-kicker">Authenticity</p>
              <p class="mt-2 text-base text-luxe-pearl/90">Carefully sourced products with quality-first selection.</p>
            </div>
            <div class="luxe-surface rounded-3xl p-6">
              <p class="luxe-kicker">Shipping</p>
              <p class="mt-2 text-base text-luxe-pearl/90">Fast dispatch and tracked delivery where available.</p>
            </div>
            <div class="luxe-surface rounded-3xl p-6">
              <p class="luxe-kicker">Support</p>
              <p class="mt-2 text-base text-luxe-pearl/90">Concierge-style help via WhatsApp for quick answers.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- 2. Bento lookbook -->
      <BentoGrid :images="bentoImages" :tagline="tagline" />

      <!-- 3. Spotlight – one hero product or CTA -->
      <Spotlight :product="spotlightProduct" :images="spotlightImages" :section="homeSections.spotlight" />

      <!-- 4. Featured products carousel -->
      <FeaturedProductsCarousel
        v-if="featuredProducts.length > 0"
        theme="editorial"
        :products="featuredProducts"
        title="Featured furniture"
        eyebrow="In the spotlight"
        subtitle="Handpicked sofas and furniture from our collection. Visit our Lusaka showroom or browse online."
      />

      <!-- 5. Journey – horizontal "idea to home" -->
      <JourneyStrip :section="homeSections.journey_strip" />

      <!-- 6. Why us – three pillars -->
      <WhyPillars :section="homeSections.why_pillars" />

      <!-- 7. Showroom reels – horizontal strip -->
      <ReelsStrip :videos="videoReels" />

      <!-- 8. One big quote -->
      <QuoteBlock :testimonials="testimonials" />

      <!-- 9. Delivery line -->
      <DeliveryLine :regions="regions" :section="homeSections.delivery_line" />

      <!-- 10. Final CTA -->
      <FinalCta :section="homeSections.final_cta" />
    </div>
  </AppLayout>
</template>

<style scoped>
.home-luxe :deep(.revealed) {
  animation: reveal-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
</style>
