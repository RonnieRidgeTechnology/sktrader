<script setup>
import { computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import { storageUrl } from '@/utils/storageUrl';
import { MapPin, Truck, Award, Heart, Shield, Sparkles, ArrowUpRight } from 'lucide-vue-next';

const props = defineProps({
  title: { type: String, default: 'About Us' },
  pageContent: { type: Object, default: () => ({}) },
});

const c = computed(() => props.pageContent || {});

const heroImageUrl = computed(() => {
  const path = c.value.hero_image;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
});

const heroTitle = computed(() => c.value.hero_title || 'About Us');
const heroSubtitle = computed(() =>
  c.value.hero_subtitle ||
  'SK Traders curates premium watches, perfumes, and skin care serums — designed for everyday confidence and elevated gifting.'
);

const ownerName = computed(() => c.value.owner_name || 'Mary Daka');

const ownerImageUrl = computed(() => {
  const path = c.value.owner_image;
  if (path && typeof path === 'string' && path.trim() !== '') {
    return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
  }
  return '/media/page-content/about/owner.png';
});

const intro = computed(() =>
  c.value.intro ||
  'We curate **watches**, **perfumes**, and **skin care serums** with a quality-first lens. Our goal is simple: make it easy to discover premium picks, choose with confidence, and enjoy a smooth, modern buying experience.'
);

const heroEyebrow = computed(() => c.value.hero_eyebrow || 'Who we are');
const storyHeading = computed(() => c.value.story_heading || 'Our story');
const meetOwnerHeading = computed(() => c.value.meet_owner_heading || 'Meet the owner');
const ownerTitle = computed(() => c.value.owner_title || 'Owner, SK Traders');
const ownerBio = computed(() => c.value.owner_bio || 'SK Traders curates luxury essentials — watches, perfumes, and serums — with a focus on quality, presentation, and customer support.');
const statsShowroomTitle = computed(() => c.value.stats_showroom_title || 'Curated drops');
const statsShowroomDesc = computed(() => c.value.stats_showroom_desc || 'Focused selection, premium presentation');
const statsDeliveryTitle = computed(() => c.value.stats_delivery_title || 'Careful delivery');
const statsDeliveryDesc = computed(() => c.value.stats_delivery_desc || 'Packed with care, delivered with confidence');
const statsQualityTitle = computed(() => c.value.stats_quality_title || 'Concierge support');
const statsQualityDesc = computed(() => c.value.stats_quality_desc || 'Fast help via WhatsApp when needed');
const facilitiesHeading = computed(() => c.value.facilities_heading || 'What we offer');
const facilitiesIntro = computed(() => c.value.facilities_intro || 'A premium selection built around clarity, confidence, and a smooth checkout experience.');
const facilitiesList = computed(() => {
  const raw = c.value.facilities_list || 'Watches: Refined timepieces for daily and occasion wear\nPerfumes: Signature scents from fresh to deep profiles\nSerums: Routine-friendly skin care essentials\nSupport: Concierge help via WhatsApp\nDelivery: Careful packing and reliable dispatch';
  return raw.split('\n').filter(Boolean).map((line) => {
    const colon = line.indexOf(':');
    if (colon > 0) return { name: line.slice(0, colon).trim(), desc: line.slice(colon + 1).trim() };
    return { name: '', desc: line.trim() };
  });
});

const commitmentHeading = computed(() => c.value.commitment_heading || 'Our Commitment');
const commitmentSubtitle = computed(() =>
  c.value.commitment_subtitle || 'What we stand for and what you can expect when you shop with us.'
);
const commitmentItems = computed(() => {
  const raw = c.value.commitment_items;
  const defaults = [
    { title: 'Quality-first selection', body: 'We curate products for quality and presentation so every order feels premium.', icon: Award },
    { title: 'Clear communication', body: 'Straightforward details and responsive support — before and after purchase.', icon: Shield },
    { title: 'Customer focus', body: 'Concierge-style help for sizes, scent profiles, and routine picks.', icon: Heart },
    { title: 'Premium experience', body: 'A modern storefront with careful packing and smooth delivery flow.', icon: Sparkles },
  ];
  if (!Array.isArray(raw) || !raw.length) return defaults;
  return raw.slice(0, 4).map((item, i) => ({
    title: (item?.title || '').trim() || defaults[i]?.title,
    body: (item?.body || '').trim() || defaults[i]?.body,
    icon: defaults[i]?.icon || Award,
  }));
});

function renderIntro(html) {
  return String(html || '').replace(/\*\*(.+?)\*\*/g, '<strong class="text-luxe-pearl font-semibold">$1</strong>');
}

const pageSeoProps = usePageSeo(null, {
  title: 'About Us | SK Traders',
  description: (props.pageContent?.hero_subtitle || 'SK Traders curates premium watches, perfumes, and skin care serums — luxury essentials with a modern, premium experience.').slice(0, 160),
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <!-- Section 1: Hero -->
    <section class="about-hero relative overflow-hidden bg-luxe-obsidian border-b border-white/10">
      <!-- Background: cinematic + grain -->
      <div class="absolute inset-0 bg-luxe-radial">
        <div v-if="heroImageUrl" class="absolute inset-0">
          <img :src="heroImageUrl" alt="" class="h-full w-full object-cover opacity-35" fetchpriority="high" />
        </div>
        <div v-else class="absolute inset-0 bg-gradient-to-br from-luxe-carbon via-luxe-obsidian to-black" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/45 to-black/75" aria-hidden="true" />
        <div class="absolute inset-0 opacity-[0.08] mix-blend-overlay [background-image:url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2260%22%20height%3D%2260%22%3E%3Cfilter%20id%3D%22n%22%3E%3CfeTurbulence%20type%3D%22fractalNoise%22%20baseFrequency%3D%220.8%22%20numOctaves%3D%222%22%20stitchTiles%3D%22stitch%22/%3E%3C/filter%3E%3Crect%20width%3D%2260%22%20height%3D%2260%22%20filter%3D%22url(%23n)%22%20opacity%3D%221%22/%3E%3C/svg%3E')]" aria-hidden="true" />
      </div>

      <div class="relative">
        <div class="luxe-container py-18 sm:py-22 lg:py-26">
          <div class="grid items-end gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-7">
              <div class="luxe-surface-strong rounded-5xl p-7 sm:p-10">
                <p class="luxe-kicker">{{ heroEyebrow }}</p>
                <h1 class="luxe-title mt-4 text-[clamp(2.1rem,4.2vw,3.6rem)] font-semibold leading-[1.05]">
                  {{ heroTitle }}
                </h1>
                <p class="mt-5 max-w-2xl text-base text-luxe-pearl/80 sm:text-lg">
                  {{ heroSubtitle }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                  <a :href="route('products')" class="luxe-btn luxe-btn-primary">
                    Shop now <span class="ml-1">→</span>
                  </a>
                  <a :href="route('contact')" class="luxe-btn luxe-btn-ghost">
                    Talk to us <ArrowUpRight class="h-4 w-4" stroke-width="2" />
                  </a>
                </div>
              </div>
            </div>

            <div class="lg:col-span-5">
              <div class="rounded-5xl border border-white/12 bg-black/30 p-6 backdrop-blur-2xl">
                <p class="luxe-kicker">At a glance</p>
                <div class="mt-5 grid gap-3">
                  <div class="flex items-start gap-4 rounded-4xl border border-white/10 bg-white/5 p-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                      <MapPin class="h-6 w-6" stroke-width="2" />
                    </span>
                    <div class="min-w-0">
                      <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsShowroomTitle }}</p>
                      <p class="mt-1 text-sm text-luxe-mist/85">{{ statsShowroomDesc }}</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-4 rounded-4xl border border-white/10 bg-white/5 p-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                      <Truck class="h-6 w-6" stroke-width="2" />
                    </span>
                    <div class="min-w-0">
                      <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsDeliveryTitle }}</p>
                      <p class="mt-1 text-sm text-luxe-mist/85">{{ statsDeliveryDesc }}</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-4 rounded-4xl border border-white/10 bg-white/5 p-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                      <Award class="h-6 w-6" stroke-width="2" />
                    </span>
                    <div class="min-w-0">
                      <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsQualityTitle }}</p>
                      <p class="mt-1 text-sm text-luxe-mist/85">{{ statsQualityDesc }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 2: Our story -->
    <section class="border-b border-white/10 bg-luxe-obsidian">
      <div class="luxe-container py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-4xl">
          <p class="luxe-kicker">Our story</p>
          <h2 id="our-story-heading" class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
            {{ storyHeading }}
          </h2>
          <div class="mt-8 luxe-surface rounded-5xl p-7 sm:p-10">
            <div class="prose-luxe text-base leading-relaxed text-luxe-pearl/85 sm:text-lg" v-html="renderIntro(intro)" />
          </div>
        </div>
      </div>
    </section>

    <!-- Section 3: Meet the owner -->
    <section class="border-b border-white/10 bg-luxe-obsidian">
      <div class="luxe-container py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-6xl">
          <div class="flex flex-col items-center justify-between gap-6 sm:flex-row sm:items-end">
            <div class="min-w-0">
              <p class="luxe-kicker">Meet the owner</p>
              <h2 id="meet-owner-heading" class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
                {{ meetOwnerHeading }}
              </h2>
              <p class="mt-3 max-w-2xl text-sm text-luxe-pearl/70 sm:text-base">
                The people behind the curation — details that make the experience feel premium.
              </p>
            </div>
          </div>

          <div class="mt-10 grid gap-8 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-5">
              <div class="relative overflow-hidden rounded-5xl border border-white/10 bg-black/30">
                <img
                  v-if="ownerImageUrl"
                  :src="ownerImageUrl"
                  :alt="ownerName"
                  class="h-[340px] w-full object-cover object-top sm:h-[420px]"
                  loading="lazy"
                />
                <div v-else class="flex h-[340px] w-full items-center justify-center sm:h-[420px]">
                  <span class="font-display text-5xl font-semibold text-luxe-gold">{{ ownerName.charAt(0) }}</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent" aria-hidden="true" />
              </div>
            </div>
            <div class="lg:col-span-7">
              <div class="luxe-surface-strong rounded-5xl p-7 sm:p-10">
                <p class="font-display text-2xl font-semibold text-luxe-pearl">{{ ownerName }}</p>
                <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] text-luxe-gold/90">
                  {{ ownerTitle }}
                </p>
                <p class="mt-5 text-base leading-relaxed text-luxe-pearl/80">
                  {{ ownerBio }}
                </p>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                  <div class="rounded-4xl border border-white/10 bg-white/5 p-5">
                    <p class="luxe-kicker">Focus</p>
                    <p class="mt-2 text-sm text-luxe-pearl/80">Curated watches, perfumes, and serums — gift-ready picks.</p>
                  </div>
                  <div class="rounded-4xl border border-white/10 bg-white/5 p-5">
                    <p class="luxe-kicker">Support</p>
                    <p class="mt-2 text-sm text-luxe-pearl/80">Concierge help via WhatsApp for fast, confident choices.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 4: Stats strip -->
    <section class="border-b border-white/10 bg-luxe-obsidian" aria-label="Presence">
      <div class="luxe-container py-12 sm:py-14">
        <div class="grid gap-4 sm:grid-cols-3">
          <div class="luxe-surface rounded-4xl p-6">
            <div class="flex items-start gap-4">
              <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                <MapPin class="h-6 w-6" stroke-width="2" />
              </span>
              <div class="min-w-0">
                <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsShowroomTitle }}</p>
                <p class="mt-1 text-sm text-luxe-mist/85">{{ statsShowroomDesc }}</p>
              </div>
            </div>
          </div>
          <div class="luxe-surface rounded-4xl p-6">
            <div class="flex items-start gap-4">
              <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                <Truck class="h-6 w-6" stroke-width="2" />
              </span>
              <div class="min-w-0">
                <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsDeliveryTitle }}</p>
                <p class="mt-1 text-sm text-luxe-mist/85">{{ statsDeliveryDesc }}</p>
              </div>
            </div>
          </div>
          <div class="luxe-surface rounded-4xl p-6">
            <div class="flex items-start gap-4">
              <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                <Award class="h-6 w-6" stroke-width="2" />
              </span>
              <div class="min-w-0">
                <p class="font-display text-base font-semibold text-luxe-pearl">{{ statsQualityTitle }}</p>
                <p class="mt-1 text-sm text-luxe-mist/85">{{ statsQualityDesc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 5: What we offer -->
    <section class="border-b border-white/10 bg-luxe-obsidian" aria-labelledby="what-we-offer-heading">
      <div class="luxe-container py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-6xl">
          <div class="text-center">
            <p class="luxe-kicker">What we offer</p>
            <h2 id="what-we-offer-heading" class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">
              {{ facilitiesHeading }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm text-luxe-pearl/75 sm:text-base">
              {{ facilitiesIntro }}
            </p>
          </div>

          <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div
              v-for="(item, i) in facilitiesList"
              :key="i"
              class="group luxe-surface rounded-5xl p-7 transition hover:bg-white/10"
            >
              <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                  <MapPin v-if="item.name && item.name.toLowerCase().includes('lusaka')" class="h-6 w-6" stroke-width="2" />
                  <Truck v-else class="h-6 w-6" stroke-width="2" />
                </span>
                <div class="min-w-0">
                  <p v-if="item.name" class="font-display text-base font-semibold text-luxe-pearl">
                    {{ item.name }}
                  </p>
                  <p class="mt-2 text-sm leading-relaxed text-luxe-pearl/75">
                    {{ item.desc }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 6: Our commitment -->
    <section class="border-b border-white/10 bg-luxe-obsidian" aria-labelledby="commitment-heading">
      <div class="luxe-container py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-6xl">
          <div class="text-center">
            <p class="luxe-kicker">Our values</p>
            <h2 id="commitment-heading" class="luxe-title mt-3 text-2xl font-semibold tracking-tight sm:text-3xl lg:text-4xl">
              {{ commitmentHeading }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm text-luxe-pearl/75 sm:text-base">
              {{ commitmentSubtitle }}
            </p>
          </div>

          <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div
              v-for="(item, i) in commitmentItems"
              :key="i"
              class="group luxe-surface rounded-5xl p-7 transition hover:bg-white/10"
            >
              <span class="flex h-12 w-12 items-center justify-center rounded-3xl border border-white/10 bg-black/30 text-luxe-gold">
                <component :is="item.icon" class="h-6 w-6" stroke-width="2" />
              </span>
              <h3 class="font-display mt-5 text-base font-semibold text-luxe-pearl">
                {{ item.title }}
              </h3>
              <p class="mt-3 text-sm leading-relaxed text-luxe-pearl/75">
                {{ item.body }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 6: CTA -->
    <CtaBanner />
  </AppLayout>
</template>

<style scoped>
.about-hero {
  min-height: clamp(520px, 80vh, 820px);
}
</style>
