<script setup>
import { computed } from 'vue';
import SeoHead from '@/Components/SeoHead.vue';
import { usePageSeo } from '@/composables/usePageSeo';
import AppLayout from '@/Layouts/AppLayout.vue';
import CtaBanner from '@/Components/CtaBanner.vue';
import { storageUrl } from '@/utils/storageUrl';
import { MapPin, Truck, Award, Heart, Shield, Sparkles } from 'lucide-vue-next';

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
  'Atlantic Garden Furniture is a furniture store in Zambia, specialising in sofas and living room furniture for homes and businesses.'
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
  'Based in **Lusaka**, we bring you a curated range of **sofas**, **armchairs**, and **living room furniture** to suit every style and budget. We focus on comfort, durability and value so you can create the home you love. Whether you are furnishing your first home or upgrading your space, we are here to help.'
);

const heroEyebrow = computed(() => c.value.hero_eyebrow || 'Who we are');
const storyHeading = computed(() => c.value.story_heading || 'Our story');
const meetOwnerHeading = computed(() => c.value.meet_owner_heading || 'Meet the owner');
const ownerTitle = computed(() => c.value.owner_title || 'Owner, Atlantic Garden Furniture');
const ownerBio = computed(() => c.value.owner_bio || 'Atlantic Garden Furniture is a furniture store specialising in sofas and living room furniture. We serve customers from our Lusaka showroom and deliver nationwide across Zambia.');
const statsShowroomTitle = computed(() => c.value.stats_showroom_title || 'Lusaka showroom');
const statsShowroomDesc = computed(() => c.value.stats_showroom_desc || 'Visit us to see and try our furniture');
const statsDeliveryTitle = computed(() => c.value.stats_delivery_title || 'Nationwide delivery');
const statsDeliveryDesc = computed(() => c.value.stats_delivery_desc || 'We deliver across Zambia');
const statsQualityTitle = computed(() => c.value.stats_quality_title || 'Quality sofas');
const statsQualityDesc = computed(() => c.value.stats_quality_desc || 'Durable materials, solid construction');
const facilitiesHeading = computed(() => c.value.facilities_heading || 'What we offer');
const facilitiesIntro = computed(() => c.value.facilities_intro || 'From our Lusaka base we serve homes and businesses across the country.');
const facilitiesList = computed(() => {
  const raw = c.value.facilities_list || 'Lusaka: Showroom and main store\nNationwide: Delivery across Zambia';
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
    { title: 'Quality First', body: 'We choose durable materials and solid construction so your sofas and furniture last for years and look great in your home.', icon: Award },
    { title: 'Honest Service', body: 'Clear pricing, delivery timelines and straightforward communication. We keep you informed from order to delivery.', icon: Shield },
    { title: 'Customer Focus', body: 'We are here to help you find the right pieces — whether you visit our showroom in Lusaka or order from home.', icon: Heart },
    { title: 'Value for Money', body: 'Fair prices and flexible options so you can furnish your space without compromise.', icon: Sparkles },
  ];
  if (!Array.isArray(raw) || !raw.length) return defaults;
  return raw.slice(0, 4).map((item, i) => ({
    title: (item?.title || '').trim() || defaults[i]?.title,
    body: (item?.body || '').trim() || defaults[i]?.body,
    icon: defaults[i]?.icon || Award,
  }));
});

function renderIntro(html) {
  return html.replace(/\*\*(.+?)\*\*/g, '<strong class="text-[#1c1917] font-semibold">$1</strong>');
}

const pageSeoProps = usePageSeo(null, {
  title: 'About Us | Atlantic Garden Furniture',
  description: (props.pageContent?.hero_subtitle || 'Atlantic Garden Furniture – your trusted furniture store in Zambia. Quality sofas and living room furniture in Lusaka and nationwide.').slice(0, 160),
});
</script>

<template>
  <AppLayout>
    <SeoHead v-bind="pageSeoProps" />

    <!-- Section 1: Hero -->
    <section class="about-hero relative min-h-[50vh] overflow-hidden border-b border-[#1c1917]/20 bg-[#0f0e0d]">
      <div v-if="heroImageUrl" class="absolute inset-0">
        <img
          :src="heroImageUrl"
          alt=""
          class="h-full w-full object-cover opacity-40"
          fetchpriority="high"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0e0d] via-[#0f0e0d]/80 to-[#0f0e0d]/50" aria-hidden="true" />
      </div>
      <div v-else class="absolute inset-0 bg-gradient-to-br from-[#1a1918] via-[#151413] to-[#0f0e0d]" aria-hidden="true" />
      <div class="absolute left-0 right-0 top-0 h-px bg-white/10" aria-hidden="true" />
      <div class="relative mx-auto flex min-h-[50vh] max-w-7xl flex-col justify-end px-4 pb-16 pt-24 sm:px-6 sm:pb-20 sm:pt-28 lg:px-8 lg:pb-24 lg:pt-32">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#f5f2ed]/60">
          {{ heroEyebrow }}
        </p>
        <h1 class="font-editorial mt-3 max-w-4xl text-3xl font-semibold tracking-tight text-[#f5f2ed] sm:text-4xl lg:text-5xl">
          {{ heroTitle }}
        </h1>
        <p class="mt-5 max-w-2xl text-lg leading-relaxed text-[#f5f2ed]/85">
          {{ heroSubtitle }}
        </p>
      </div>
    </section>

    <!-- Section 2: Our Story -->
    <section class="relative border-b border-[#1c1917]/10 bg-[#faf8f5] px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24" aria-labelledby="our-story-heading">
      <div class="mx-auto max-w-3xl">
        <h2 id="our-story-heading" class="font-editorial text-2xl font-semibold tracking-tight text-[#1c1917] sm:text-3xl">
          {{ storyHeading }}
        </h2>
        <div class="mt-2 h-1 w-16 bg-[#c2410c]" aria-hidden="true" />
        <div
          class="prose-editorial mt-8 text-lg leading-relaxed text-[#44403c]"
          v-html="renderIntro(intro)"
        />
      </div>
    </section>

    <!-- Section 2b: Meet the owner -->
    <section class="relative border-b border-[#1c1917]/10 bg-white px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24" aria-labelledby="meet-owner-heading">
      <div class="mx-auto max-w-4xl">
        <h2 id="meet-owner-heading" class="font-editorial text-center text-2xl font-semibold tracking-tight text-[#1c1917] sm:text-3xl">
          {{ meetOwnerHeading }}
        </h2>
        <div class="mx-auto mt-4 h-1 w-16 bg-[#c2410c]" aria-hidden="true" />
        <div class="mt-10 flex flex-col items-center gap-8 sm:flex-row sm:items-start sm:gap-12">
          <div class="relative h-56 w-56 shrink-0 overflow-hidden border-2 border-[#1c1917]/10 bg-[#f5f2ed] sm:h-72 sm:w-72">
            <img
              v-if="ownerImageUrl"
              :src="ownerImageUrl"
              :alt="ownerName"
              class="h-full w-full object-cover object-top"
              loading="lazy"
            />
            <div
              v-else
              class="flex h-full w-full items-center justify-center text-4xl font-semibold text-[#c2410c]"
              aria-hidden="true"
            >
              {{ ownerName.charAt(0) }}
            </div>
          </div>
          <div class="min-w-0 flex-1 text-center sm:text-left">
            <p class="font-editorial text-xl font-semibold text-[#1c1917] sm:text-2xl">
              {{ ownerName }}
            </p>
            <p class="mt-2 text-[#c2410c] font-medium">
              {{ ownerTitle }}
            </p>
            <p class="mt-4 text-[#44403c] leading-relaxed">
              {{ ownerBio }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 3: Stats strip -->
    <section class="relative border-b border-[#1c1917]/10 bg-white px-4 py-10 sm:px-6 sm:py-12 lg:px-8" aria-label="Presence">
      <div class="mx-auto max-w-5xl">
        <div class="grid gap-6 sm:grid-cols-3">
          <div class="flex flex-col items-center text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-none bg-[#c2410c] text-white">
              <MapPin class="h-7 w-7" stroke-width="2" />
            </span>
            <p class="mt-4 font-editorial text-lg font-semibold text-[#1c1917]">{{ statsShowroomTitle }}</p>
            <p class="mt-1 text-sm text-[#44403c]">{{ statsShowroomDesc }}</p>
          </div>
          <div class="flex flex-col items-center text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-none bg-[#c2410c] text-white">
              <Truck class="h-7 w-7" stroke-width="2" />
            </span>
            <p class="mt-4 font-editorial text-lg font-semibold text-[#1c1917]">{{ statsDeliveryTitle }}</p>
            <p class="mt-1 text-sm text-[#44403c]">{{ statsDeliveryDesc }}</p>
          </div>
          <div class="flex flex-col items-center text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-none bg-[#c2410c] text-white">
              <Award class="h-7 w-7" stroke-width="2" />
            </span>
            <p class="mt-4 font-editorial text-lg font-semibold text-[#1c1917]">{{ statsQualityTitle }}</p>
            <p class="mt-1 text-sm text-[#44403c]">{{ statsQualityDesc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 4: What we offer -->
    <section class="relative border-b border-[#1c1917]/10 bg-[#f5f2ed] px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24" aria-labelledby="what-we-offer-heading">
      <div class="mx-auto max-w-5xl">
        <h2 id="what-we-offer-heading" class="font-editorial text-center text-2xl font-semibold tracking-tight text-[#1c1917] sm:text-3xl">
          {{ facilitiesHeading }}
        </h2>
        <p class="mx-auto mt-3 max-w-xl text-center text-[#44403c]">
          {{ facilitiesIntro }}
        </p>
        <div class="mt-10 grid gap-6 sm:grid-cols-2">
          <div
            v-for="(item, i) in facilitiesList"
            :key="i"
            class="group relative flex gap-5 border-2 border-[#1c1917]/10 bg-white p-6 transition hover:border-[#c2410c]/30 hover:shadow-lg sm:p-8"
          >
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-none bg-[#c2410c] text-white transition group-hover:bg-[#a83609]">
              <MapPin v-if="item.name && item.name.toLowerCase().includes('lusaka')" class="h-6 w-6" stroke-width="2" />
              <Truck v-else class="h-6 w-6" stroke-width="2" />
            </span>
            <div class="min-w-0 flex-1">
              <h3 v-if="item.name" class="font-editorial text-lg font-semibold text-[#1c1917]">
                {{ item.name }}
              </h3>
              <p class="mt-2 text-[#44403c]">
                {{ item.desc }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 5: Our Commitment -->
    <section class="relative border-b border-[#1c1917]/10 bg-white px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24" aria-labelledby="commitment-heading">
      <div class="mx-auto max-w-6xl">
        <div class="text-center">
          <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#c2410c]">
            Our values
          </p>
          <h2 id="commitment-heading" class="font-editorial mt-2 text-2xl font-semibold tracking-tight text-[#1c1917] sm:text-3xl lg:text-4xl">
            {{ commitmentHeading }}
          </h2>
          <p class="mx-auto mt-4 max-w-2xl text-[#44403c]">
            {{ commitmentSubtitle }}
          </p>
          <div class="mx-auto mt-4 h-px w-20 bg-[#c2410c]/60" aria-hidden="true" />
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="(item, i) in commitmentItems"
            :key="i"
            class="group flex flex-col border border-[#1c1917]/10 bg-[#faf8f5] p-6 transition hover:border-[#c2410c]/25 hover:shadow-md lg:p-8"
          >
            <span class="flex h-12 w-12 items-center justify-center rounded-none bg-[#c2410c] text-white">
              <component :is="item.icon" class="h-6 w-6" stroke-width="2" />
            </span>
            <h3 class="font-editorial mt-5 text-lg font-semibold text-[#1c1917]">
              {{ item.title }}
            </h3>
            <p class="mt-3 flex-1 text-sm leading-relaxed text-[#44403c]">
              {{ item.body }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 6: CTA -->
    <CtaBanner />
  </AppLayout>
</template>

<style scoped>
.prose-editorial :deep(strong) {
  @apply font-semibold text-[#1c1917];
}
.about-hero {
  min-height: clamp(320px, 50vh, 420px);
}
</style>
