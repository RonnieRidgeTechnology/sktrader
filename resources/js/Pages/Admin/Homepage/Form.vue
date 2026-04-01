<script setup>
import { computed, ref, inject } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FormCard from '@/Components/Admin/FormCard.vue';
import { storageUrl, mediaUrl } from '@/utils/storageUrl';
import { Layout, Type, AlignLeft, ListOrdered, Eye, Image } from 'lucide-vue-next';

function safeRoute(name, ...params) {
  if (typeof window !== 'undefined' && typeof window.route === 'function') {
    if (arguments.length === 0) return window.route();
    return window.route(name, ...params);
  }
  return arguments.length === 0 ? { current: null } : '#';
}
const route = inject('route') ?? safeRoute;

const props = defineProps({ section: Object });
const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? null);
const flashError = computed(() => page.props.flash?.error ?? null);
/** Validation errors when form is submitted via router.post (hero or section with files) */
const heroErrors = computed(() => page.props.errors ?? {});
const sectionErrors = computed(() => page.props.errors ?? {});
/** First error message for hero fields other than image_left / image_right (e.g. gallery file size) */
const heroOtherError = computed(() => {
  const err = heroErrors.value;
  const keys = Object.keys(err).filter((k) => k !== 'image_left' && k !== 'image_right');
  for (const k of keys) {
    const v = err[k];
    const msg = Array.isArray(v) ? v[0] : v;
    if (msg) return msg;
  }
  return null;
});

const sectionKey = computed(() => props.section?.key ?? '');
const isHero = computed(() => sectionKey.value === 'hero');
const isWhoWeWorkWith = computed(() => sectionKey.value === 'who_we_work_with');
const publicSectionKeys = ['spotlight', 'journey_strip', 'why_pillars', 'reels_strip', 'quote_block', 'delivery_line', 'final_cta'];
const isPublicSection = computed(() => publicSectionKeys.includes(sectionKey.value));
const isSpotlight = computed(() => sectionKey.value === 'spotlight');
const isJourneyStrip = computed(() => sectionKey.value === 'journey_strip');
const isWhyPillars = computed(() => sectionKey.value === 'why_pillars');
const isReelsStrip = computed(() => sectionKey.value === 'reels_strip');
const isQuoteBlock = computed(() => sectionKey.value === 'quote_block');
const isDeliveryLine = computed(() => sectionKey.value === 'delivery_line');
const isFinalCta = computed(() => sectionKey.value === 'final_cta');
const heroData = computed(() => props.section?.data ?? {});
const whoWeWorkWithData = computed(() => props.section?.data?.items ?? []);

const d = computed(() => props.section?.data ?? {});
const journeySteps = computed(() => (d.value.steps || []).length >= 5 ? d.value.steps : [
  { title: 'Discover', line: 'Browse curated watches, perfumes, and serums.' },
  { title: 'Choose', line: 'Pick your style and preferences.' },
  { title: 'Confirm order', line: 'We arrange payment and delivery.' },
  { title: 'We prepare', line: 'Quality checks and careful packing.' },
  { title: 'Delivery', line: 'To your door across Zambia.' },
]);
const whyPillars = computed(() => (d.value.pillars || []).length >= 3 ? d.value.pillars : [
  { title: 'Curated selection', text: 'Focused picks across watches, perfumes, and serums — chosen for quality and premium presentation.' },
  { title: 'Concierge support', text: 'Quick help via WhatsApp — sizes, scent profiles, routine tips, and order updates.' },
  { title: 'Nationwide delivery', text: 'We deliver across Zambia. From Lusaka to your door, we get it there safely.' },
]);
const deliveryRegions = computed(() => Array.isArray(d.value.regions) && d.value.regions.length ? d.value.regions.join('\n') : 'Lusaka\nCopperbelt\nSouthern\nCentral\nNorthern\nNationwide');

const form = useForm({
  title: props.section?.title ?? '',
  content: props.section?.content ?? '',
  visible: props.section !== undefined && props.section !== null ? props.section.visible : true,
  sort_order: props.section?.sort_order ?? 0,
  _method: 'PUT',
  headline: heroData.value.headline ?? 'Luxury essentials, curated.',
  subheading: heroData.value.subheading ?? 'Watches, perfumes, and skin care serums — premium picks, delivered with care.',
  body: heroData.value.body ?? '',
  tagline: heroData.value.tagline ?? '',
  cta_primary: heroData.value.cta_primary ?? '',
  cta_secondary: heroData.value.cta_secondary ?? '',
  image_left: null,
  image_right: null,
  remove_image_left: false,
  remove_image_right: false,
  remove_gallery: [],
  ...Object.fromEntries(Array.from({ length: 20 }, (_, i) => ['new_gallery_' + i, null])),
  section_image: null,
  remove_section_image: false,
  ...Object.fromEntries(Array.from({ length: 6 }, (_, i) => ['who_image_' + i, null])),
  ...Object.fromEntries(Array.from({ length: 6 }, (_, i) => ['who_remove_image_' + i, false])),
  ...Object.fromEntries(Array.from({ length: 6 }, (_, i) => ['who_description_' + i, (props.section?.data?.items?.[i]?.description ?? '')])),
  // Spotlight
  eyebrow: (props.section?.data?.eyebrow ?? ''),
  heading: (props.section?.data?.heading ?? ''),
  body: (props.section?.data?.body ?? ''),
  spotlight_image: null,
  remove_spotlight_image: false,
  // Journey strip
  ...Object.fromEntries(Array.from({ length: 5 }, (_, i) => ['step_title_' + i, (props.section?.data?.steps?.[i]?.title ?? '')])),
  ...Object.fromEntries(Array.from({ length: 5 }, (_, i) => ['step_line_' + i, (props.section?.data?.steps?.[i]?.line ?? '')])),
  // Why pillars
  ...Object.fromEntries(Array.from({ length: 3 }, (_, i) => ['pillar_title_' + i, (props.section?.data?.pillars?.[i]?.title ?? '')])),
  ...Object.fromEntries(Array.from({ length: 3 }, (_, i) => ['pillar_text_' + i, (props.section?.data?.pillars?.[i]?.text ?? '')])),
  // Delivery line
  delivery_headline: (props.section?.data?.headline ?? ''),
  regions: Array.isArray(props.section?.data?.regions) ? props.section?.data?.regions.join('\n') : 'Lusaka\nCopperbelt\nSouthern\nCentral\nNorthern\nNationwide',
  footer: (props.section?.data?.footer ?? ''),
  // Final CTA
  final_headline: (props.section?.data?.headline ?? ''),
  primary_button: (props.section?.data?.primary_button ?? ''),
  secondary_button: (props.section?.data?.secondary_button ?? ''),
});

const hasSectionImage = computed(() => ['trust', 'what_we_do'].includes(sectionKey.value));
const whoWeWorkWithLabels = ['Fitness Startups', 'Gym Owners', 'Personal Trainers', 'Online Fitness Brands', 'Amazon & Shopify Sellers', 'Distributors & Wholesalers'];
const whoWeWorkWithImageUrl = (index) => {
  const item = whoWeWorkWithData.value[index];
  const path = item?.image;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
};
// Refs for hero/gallery files so File objects are reliably sent (reactive form can lose them)
const heroLeftFileRef = ref(null);
const heroRightFileRef = ref(null);
const heroNewGalleryFilesRef = ref([]);
const heroReplaceGalleryFilesRef = ref({});

const heroGalleryCount = computed(() => (heroGalleryUrls.value ?? []).length);
const heroHasFiles = computed(() => {
  if (!isHero.value) return false;
  const hasSplit = heroLeftFileRef.value || heroRightFileRef.value || form.remove_image_left || form.remove_image_right;
  const hasGalleryRemoves = (form.remove_gallery && form.remove_gallery.length) > 0;
  const hasReplace = Object.keys(heroReplaceGalleryFilesRef.value).length > 0;
  const hasGalleryNew = heroNewGalleryFilesRef.value.length > 0;
  return hasSplit || hasGalleryRemoves || hasReplace || hasGalleryNew;
});
function setHeroLeftFile(e) {
  heroLeftFileRef.value = (e.target.files && e.target.files[0]) || null;
}
function setHeroRightFile(e) {
  heroRightFileRef.value = (e.target.files && e.target.files[0]) || null;
}
function setReplaceGalleryFile(index, e) {
  const file = (e.target.files && e.target.files[0]) || null;
  const next = { ...heroReplaceGalleryFilesRef.value };
  if (file) next[index] = file;
  else delete next[index];
  heroReplaceGalleryFilesRef.value = next;
  e.target.value = '';
}
function toggleRemoveGallery(index) {
  const arr = form.remove_gallery || [];
  const i = arr.indexOf(index);
  if (i === -1) form.remove_gallery = [...arr, index];
  else form.remove_gallery = arr.filter((x) => x !== index);
}
function isRemovedGallery(index) {
  return (form.remove_gallery || []).includes(index);
}
function onNewGalleryFiles(event) {
  const files = event.target.files ? Array.from(event.target.files) : [];
  heroNewGalleryFilesRef.value = files.slice(0, 20);
  event.target.value = '';
}
const newGalleryCount = computed(() => heroNewGalleryFilesRef.value.length);

// Hero section: build FormData; read image_left/image_right from form DOM so files are never lost
function buildHeroFormData() {
  const fd = new FormData();
  const token = page.props.csrf_token;
  if (token) fd.append('_token', token);
  fd.append('_method', 'PUT');
  // Non-file fields from form
  const nonFileKeys = [
    'title', 'content', 'visible', 'sort_order',
    'headline', 'subheading', 'body', 'tagline', 'cta_primary', 'cta_secondary',
    'remove_image_left', 'remove_image_right', 'remove_gallery',
  ];
  for (const key of nonFileKeys) {
    const value = form[key];
    if (value === undefined || value === null) continue;
    if (key === 'remove_gallery' && Array.isArray(value)) {
      value.forEach((v) => fd.append('remove_gallery[]', String(v)));
    } else if (typeof value === 'boolean') {
      fd.append(key, value ? '1' : '0');
    } else {
      fd.append(key, String(value));
    }
  }
  // Hero left/right images: use refs first (reliable), then fall back to form file inputs
  if (heroLeftFileRef.value instanceof File) {
    fd.append('image_left', heroLeftFileRef.value);
  } else {
    const formEl = document.getElementById('homepage-section-form');
    const leftInput = formEl?.querySelector('input[name="image_left"]');
    if (leftInput?.files?.length) fd.append('image_left', leftInput.files[0]);
  }
  if (heroRightFileRef.value instanceof File) {
    fd.append('image_right', heroRightFileRef.value);
  } else {
    const formEl = document.getElementById('homepage-section-form');
    const rightInput = formEl?.querySelector('input[name="image_right"]');
    if (rightInput?.files?.length) fd.append('image_right', rightInput.files[0]);
  }
  // Replace gallery and new gallery from refs
  Object.entries(heroReplaceGalleryFilesRef.value).forEach(([index, file]) => {
    if (file instanceof File) fd.append('replace_gallery_' + index, file);
  });
  heroNewGalleryFilesRef.value.forEach((file, i) => {
    if (file instanceof File && i <= 19) fd.append('new_gallery_' + i, file);
  });
  return fd;
}

function buildSectionFormData() {
  const fd = new FormData();
  const token = page.props.csrf_token;
  if (token) fd.append('_token', token);
  fd.append('_method', 'PUT');
  fd.append('title', form.title);
  fd.append('content', form.content);
  fd.append('visible', form.visible ? '1' : '0');
  fd.append('sort_order', String(form.sort_order ?? 0));
  if (hasSectionImage.value) {
    fd.append('remove_section_image', form.remove_section_image ? '1' : '0');
    if (form.section_image instanceof File) fd.append('section_image', form.section_image);
  }
  if (isWhoWeWorkWith.value) {
    for (let i = 0; i < 6; i++) {
      fd.append('who_remove_image_' + i, form['who_remove_image_' + i] ? '1' : '0');
      fd.append('who_description_' + i, String(form['who_description_' + i] ?? ''));
      if (form['who_image_' + i] instanceof File) fd.append('who_image_' + i, form['who_image_' + i]);
    }
  }
  return fd;
}

const sectionHasFileUploads = computed(() => hasSectionImage.value || isWhoWeWorkWith.value);

function buildSpotlightFormData() {
  const fd = new FormData();
  const token = page.props.csrf_token;
  if (token) fd.append('_token', token);
  fd.append('_method', 'PUT');
  fd.append('eyebrow', form.eyebrow);
  fd.append('heading', form.heading);
  fd.append('body', form.body);
  fd.append('visible', form.visible ? '1' : '0');
  fd.append('sort_order', String(form.sort_order ?? 0));
  fd.append('remove_spotlight_image', form.remove_spotlight_image ? '1' : '0');
  if (form.spotlight_image instanceof File) fd.append('spotlight_image', form.spotlight_image);
  return fd;
}

function submitSection() {
  if (!props.section) return;
  const url = route('admin.homepage.update', props.section.key);
  if (isHero.value) {
    router.post(url, buildHeroFormData(), { forceFormData: true, preserveScroll: true });
  } else if (isSpotlight.value && (form.spotlight_image || form.remove_spotlight_image)) {
    router.post(url, buildSpotlightFormData(), { forceFormData: true, preserveScroll: true });
  } else if (sectionHasFileUploads.value) {
    router.post(url, buildSectionFormData(), { forceFormData: true, preserveScroll: true });
  } else {
    form.post(url, { forceFormData: true, preserveScroll: true });
  }
}

const heroImageLeftUrl = computed(() => {
  const path = heroData.value.image_left;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : mediaUrl(path);
});
const heroImageRightUrl = computed(() => {
  const path = heroData.value.image_right;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : mediaUrl(path);
});
const heroGalleryUrls = computed(() => {
  const list = heroData.value.gallery_images ?? [];
  const arr = Array.isArray(list) ? list : [];
  return arr.map((path) =>
    path && typeof path === 'string' ? (path.startsWith('http') || path.startsWith('/') ? path : mediaUrl(path)) : null
  ).filter(Boolean);
});
const sectionImageUrl = computed(() => {
  const path = props.section?.data?.section_image;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
});
const spotlightImageUrl = computed(() => {
  const path = props.section?.data?.image;
  if (!path || typeof path !== 'string') return null;
  return path.startsWith('http') || path.startsWith('/') ? path : storageUrl(path);
});
const sectionDescription = computed(() => {
  const key = sectionKey.value;
  const descriptions = {
    hero: 'Top banner and images.',
    trust: 'Legacy section. Not shown on the current home page design.',
    cta: 'Call-to-action section (e.g. “Get a quote” or “Start your order”). Use title for the button or heading and content for the line above it.',
    what_we_do: 'Legacy section. Not shown on the current home page design.',
    who_we_work_with: 'Legacy section. Not shown on the current home page design.',
    features: 'Legacy section. Not shown on the current home page design.',
    intro: 'Legacy section. Not shown on the current home page design.',
    spotlight: 'Text and image.',
    journey_strip: '5 steps.',
    why_pillars: '3 pillars.',
    reels_strip: 'Videos from Video Reels.',
    quote_block: 'Testimonials from Testimonials.',
    delivery_line: 'Delivery message.',
    final_cta: 'Bottom buttons.',
  };
  return descriptions[key] || '';
});

const sectionLabels = {
  hero: 'Room hero & Bento',
  spotlight: 'Spotlight',
  journey_strip: 'Journey strip',
  why_pillars: 'Why us',
  reels_strip: 'Video reels',
  quote_block: 'Testimonials',
  delivery_line: 'Delivery line',
  final_cta: 'Final CTA',
  trust: 'Trust',
  what_we_do: 'What we do',
  who_we_work_with: 'Who we work with',
  features: 'Features',
  intro: 'Intro',
  cta: 'CTA',
};
function sectionLabel(key) {
  const k = key && typeof key === 'string' ? key : '';
  return sectionLabels[k] || (k ? k.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()) : '');
}

const pageTitle = computed(() => {
  if (isHero.value) return 'Edit Home Page';
  const key = sectionKey.value;
  const label = sectionLabels[key] || (key ? key.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()) : '');
  return 'Edit: ' + (label || props.section?.key || '');
});

const inputClass =
  'mt-2 block w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white';
const labelClass = 'block text-sm font-medium text-zinc-700 dark:text-zinc-300';
</script>

<template>
  <AdminLayout>
    <div v-if="!section" class="space-y-8 px-6 py-8">
      <p class="text-zinc-600 dark:text-zinc-400">Section could not be loaded. <Link :href="route('admin.homepage.index')" class="font-medium text-amber-600 hover:text-amber-500 dark:text-amber-400">Back to Homepage sections</Link>.</p>
    </div>
    <div v-else class="space-y-8">
      <PageHeader
        :title="pageTitle"
        :description="sectionDescription"
      >
        <template #actions>
          <Link
            :href="route('admin.homepage.index')"
            class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Back to Home Page
          </Link>
          <button
            type="submit"
            form="homepage-section-form"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-amber-400 disabled:opacity-70"
          >
            {{ form.processing ? 'Saving…' : 'Save section' }}
          </button>
        </template>
      </PageHeader>

      <div v-if="flashSuccess" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
        {{ flashSuccess }}
      </div>
      <div v-if="flashError" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
        {{ flashError }}
      </div>

      <form
        id="homepage-section-form"
        class="space-y-8"
        @submit.prevent="section && submitSection()"
      >
        <FormCard
          title="What this section is for"
          :description="sectionDescription"
        >
          <template #vector>
            <Layout class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="rounded-lg border border-zinc-200 bg-zinc-50/80 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800/50">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              <strong class="text-zinc-900 dark:text-white">Section key:</strong>
              <code class="ml-1 rounded bg-zinc-200 px-1.5 py-0.5 font-mono text-xs dark:bg-zinc-700">{{ sectionKey }}</code>
              — used internally to place this block on the homepage.
            </p>
          </div>
        </FormCard>

        <!-- Hero: headline + subheading (current public home – Room hero) -->
        <FormCard
          v-if="isHero"
          title="Hero text"
          description="The two lines shown on the full-screen hero at the top of the home page (e.g. “Curated luxury · Limited drops”)."
        >
          <template #vector>
            <Type class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Headline</label>
              <input v-model="form.headline" type="text" :class="inputClass" placeholder="Luxury essentials, curated." />
            </div>
            <div>
              <label :class="labelClass">Subheading</label>
              <input v-model="form.subheading" type="text" :class="inputClass" placeholder="Watches, perfumes, and skin care serums — premium picks, delivered with care." />
            </div>
          </div>
        </FormCard>

        <!-- Hero: images -->
        <FormCard v-if="isHero" title="Images (left, right, gallery)">
          <template #vector>
            <Image class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-8">
            <p v-if="heroOtherError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
              {{ heroOtherError }}
            </p>
            <div>
              <label :class="labelClass">Left image</label>
              <input
                name="image_left"
                type="file"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="mt-2 block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                @change="setHeroLeftFile($event)"
              />
              <div v-if="heroImageLeftUrl && !heroLeftFileRef" class="mt-3 flex flex-wrap items-center gap-4">
                <img :src="heroImageLeftUrl" alt="Left hero" class="max-h-32 rounded-lg border border-zinc-200 object-cover dark:border-zinc-600" />
                <label class="flex cursor-pointer items-center gap-2 text-sm text-red-600 dark:text-red-400">
                  <input v-model="form.remove_image_left" type="checkbox" class="rounded border-zinc-300" />
                  Remove image
                </label>
              </div>
              <p v-if="heroLeftFileRef" class="mt-2 text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
              <p v-if="heroErrors.image_left" class="mt-2 text-sm text-red-500">{{ Array.isArray(heroErrors.image_left) ? heroErrors.image_left[0] : heroErrors.image_left }}</p>
            </div>
            <div>
              <label :class="labelClass">Right image</label>
              <input
                name="image_right"
                type="file"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="mt-2 block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                @change="setHeroRightFile($event)"
              />
              <div v-if="heroImageRightUrl && !heroRightFileRef" class="mt-3 flex flex-wrap items-center gap-4">
                <img :src="heroImageRightUrl" alt="Right hero" class="max-h-32 rounded-lg border border-zinc-200 object-cover dark:border-zinc-600" />
                <label class="flex cursor-pointer items-center gap-2 text-sm text-red-600 dark:text-red-400">
                  <input v-model="form.remove_image_right" type="checkbox" class="rounded border-zinc-300" />
                  Remove image
                </label>
              </div>
              <p v-if="heroRightFileRef" class="mt-2 text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
              <p v-if="heroErrors.image_right" class="mt-2 text-sm text-red-500">{{ Array.isArray(heroErrors.image_right) ? heroErrors.image_right[0] : heroErrors.image_right }}</p>
            </div>
          </div>
        </FormCard>

        <!-- Hero: gallery below hero – CRUD, slider shows 5 per slide -->
        <FormCard
          v-if="isHero"
          title="Gallery"
          description="Photos shown in a full-width slider right after the hero. You can add many images (10+); the slider shows 5 at a time and slides to the next set. Replace or remove any image. The last visible image on each slide can show a “Chat with us” WhatsApp button."
        >
          <template #vector>
            <Image class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-6">
            <!-- Current images: replace / remove -->
            <div v-if="heroGalleryUrls.length > 0" class="space-y-4">
              <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Current ({{ heroGalleryUrls.length }})</p>
              <div
                v-for="(url, index) in heroGalleryUrls"
                :key="index"
                class="flex flex-wrap items-start gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50"
                :class="isRemovedGallery(index) && 'opacity-60'"
              >
                <div class="aspect-video w-28 shrink-0 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-800">
                  <img v-if="!isRemovedGallery(index)" :src="url" :alt="'Gallery ' + (index + 1)" class="h-full w-full object-cover" />
                  <div v-else class="flex h-full items-center justify-center text-xs text-zinc-500">Removed</div>
                </div>
                <div class="min-w-0 flex-1 space-y-2">
                  <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Picture {{ index + 1 }}</p>
                  <div class="flex flex-wrap gap-2">
                    <template v-if="!isRemovedGallery(index)">
                      <label class="cursor-pointer">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">Replace</span>
                        <input
                          type="file"
                          accept="image/*"
                          class="hidden"
                          @change="setReplaceGalleryFile(index, $event)"
                        />
                      </label>
                      <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400"
                        @click="toggleRemoveGallery(index)"
                      >
                        Remove
                      </button>
                    </template>
                    <button
                      v-else
                      type="button"
                      class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                      @click="toggleRemoveGallery(index)"
                    >
                      Undo remove
                    </button>
                  </div>
                  <p v-if="heroReplaceGalleryFilesRef[index]" class="text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
                </div>
              </div>
            </div>

            <div>
              <label :class="labelClass">Add images</label>
              <input
                type="file"
                accept="image/*"
                multiple
                class="mt-2 block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                @change="onNewGalleryFiles($event)"
              />
              <p v-if="newGalleryCount > 0" class="mt-2 text-xs text-amber-600 dark:text-amber-400">{{ newGalleryCount }} new image(s) selected (save to add)</p>
            </div>

            <p v-if="heroGalleryUrls.length === 0 && newGalleryCount === 0" class="text-sm text-zinc-500 dark:text-zinc-400">No gallery images yet. Use “Add images” above to add photos. They will appear in the hero slider and in the Bento grid on the home page.</p>
          </div>
        </FormCard>

        <!-- Spotlight -->
        <FormCard v-if="isSpotlight" title="Spotlight">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Image</label>
              <input
                type="file"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="mt-2 block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                @change="form.spotlight_image = $event.target.files?.[0] ?? null"
              />
              <div v-if="spotlightImageUrl && !form.spotlight_image" class="mt-3 flex flex-wrap items-center gap-4">
                <img :src="spotlightImageUrl" alt="Spotlight" class="max-h-32 rounded-lg border border-zinc-200 object-cover dark:border-zinc-600" />
                <label class="flex cursor-pointer items-center gap-2 text-sm text-red-600 dark:text-red-400">
                  <input v-model="form.remove_spotlight_image" type="checkbox" class="rounded border-zinc-300" />
                  Remove
                </label>
              </div>
              <p v-if="form.spotlight_image" class="mt-2 text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
            </div>
            <div>
              <label :class="labelClass">Eyebrow</label>
              <input v-model="form.eyebrow" type="text" :class="inputClass" placeholder="In the spotlight" />
            </div>
            <div>
              <label :class="labelClass">Heading</label>
              <input v-model="form.heading" type="text" :class="inputClass" placeholder="Featured piece" />
            </div>
            <div>
              <label :class="labelClass">Body</label>
              <textarea v-model="form.body" :class="inputClass" rows="2" placeholder="Optional text." />
            </div>
          </div>
        </FormCard>

        <!-- Journey strip -->
        <FormCard v-if="isJourneyStrip" title="Steps">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Eyebrow</label>
              <input v-model="form.eyebrow" type="text" :class="inputClass" placeholder="From idea to home" />
            </div>
            <div v-for="i in 5" :key="i" class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
              <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Step {{ i }}</p>
              <input v-model="form['step_title_' + (i - 1)]" type="text" :class="inputClass" class="mt-1" placeholder="Title" />
              <input v-model="form['step_line_' + (i - 1)]" type="text" :class="inputClass" class="mt-2" placeholder="Short line" />
            </div>
          </div>
        </FormCard>

        <!-- Why pillars -->
        <FormCard v-if="isWhyPillars" title="Why us">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Section heading</label>
              <input v-model="form.heading" type="text" :class="inputClass" placeholder="Curated luxury essentials, delivered with care." />
            </div>
            <div v-for="i in 3" :key="i" class="rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
              <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Pillar {{ i }}</p>
              <input v-model="form['pillar_title_' + (i - 1)]" type="text" :class="inputClass" class="mt-1" placeholder="Title" />
              <textarea v-model="form['pillar_text_' + (i - 1)]" :class="inputClass" class="mt-2" rows="2" placeholder="Text" />
            </div>
          </div>
        </FormCard>

        <!-- Reels strip & Quote block -->
        <FormCard v-if="isReelsStrip" title="Video reels">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Eyebrow (small label)</label>
              <input v-model="form.eyebrow" type="text" :class="inputClass" placeholder="Reels" />
            </div>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Videos are managed in <strong>Admin → Video Reels</strong>.</p>
          </div>
        </FormCard>
        <FormCard v-if="isQuoteBlock" title="Testimonials">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Eyebrow (small label)</label>
              <input v-model="form.eyebrow" type="text" :class="inputClass" placeholder="What people say" />
            </div>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Testimonials are managed in <strong>Admin → Testimonials</strong>.</p>
          </div>
        </FormCard>

        <!-- Delivery line -->
        <FormCard v-if="isDeliveryLine" title="Delivery">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Eyebrow</label>
              <input v-model="form.eyebrow" type="text" :class="inputClass" placeholder="We deliver across Zambia" />
            </div>
            <div>
              <label :class="labelClass">Headline</label>
              <input v-model="form.delivery_headline" type="text" :class="inputClass" placeholder="Carefully packed. Delivered with confidence." />
            </div>
            <div>
              <label :class="labelClass">Regions (one per line or comma-separated)</label>
              <textarea v-model="form.regions" :class="inputClass" rows="6" placeholder="Lusaka, Copperbelt, Southern, ..." />
            </div>
            <div>
              <label :class="labelClass">Footer line</label>
              <input v-model="form.footer" type="text" :class="inputClass" placeholder="Contact us for delivery details and lead times." />
            </div>
          </div>
        </FormCard>

        <!-- Final CTA -->
        <FormCard v-if="isFinalCta" title="Final CTA">
          <template #vector><Type class="h-12 w-12" stroke-width="1.5" /></template>
          <div class="space-y-4">
            <div>
              <label :class="labelClass">Headline</label>
              <input v-model="form.final_headline" type="text" :class="inputClass" placeholder="Ready to find your piece?" />
            </div>
            <div>
              <label :class="labelClass">Primary button (WhatsApp)</label>
              <input v-model="form.primary_button" type="text" :class="inputClass" placeholder="Chat on WhatsApp" />
            </div>
            <div>
              <label :class="labelClass">Secondary button</label>
              <input v-model="form.secondary_button" type="text" :class="inputClass" placeholder="Contact us" />
            </div>
          </div>
        </FormCard>

        <template v-if="!isHero && !isPublicSection">
          <FormCard
            title="Title"
            description="Heading or main line for this section. Shown prominently on the page."
          >
            <template #vector>
              <Type class="h-12 w-12" stroke-width="1.5" />
            </template>
            <div>
              <label for="title" :class="labelClass">Title</label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                :class="inputClass"
                placeholder="e.g. Why Choose Us"
              />
              <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">{{ form.errors.title }}</p>
            </div>
          </FormCard>

          <FormCard
            title="Content"
            description="Main text or HTML for this section. You can use tags like &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt; for formatting."
          >
            <template #vector>
              <AlignLeft class="h-12 w-12" stroke-width="1.5" />
            </template>
            <div>
              <label for="content" :class="labelClass">Content (HTML or plain text)</label>
              <textarea
                id="content"
                v-model="form.content"
                :class="inputClass"
                rows="8"
                placeholder="Enter the body content for this section…"
              />
              <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                Use simple HTML for paragraphs, bold text, and lists. Avoid complex layout tags.
              </p>
              <p v-if="form.errors.content" class="mt-1 text-sm text-red-500">{{ form.errors.content }}</p>
            </div>
          </FormCard>

          <!-- Who We Work With: one image per card -->
          <FormCard
            v-if="isWhoWeWorkWith"
            title="Card images & descriptions"
            description="Upload one image per card. Each image is shown on the homepage in the “Who We Work With” section instead of the default icon. Order: 1 = Fitness Startups, 2 = Gym Owners, 3 = Personal Trainers, 4 = Online Fitness Brands, 5 = Amazon & Shopify Sellers, 6 = Distributors & Wholesalers."
          >
            <template #vector>
              <Image class="h-12 w-12" stroke-width="1.5" />
            </template>
            <div class="space-y-6">
              <div
                v-for="(label, idx) in whoWeWorkWithLabels"
                :key="idx"
                class="flex flex-wrap items-start gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50"
              >
                <div class="w-24 shrink-0">
                  <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ label }}</p>
                  <div class="mt-2 aspect-square w-20 overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-600 dark:bg-zinc-800">
                    <img
                      v-if="whoWeWorkWithImageUrl(idx) && !form['who_image_' + idx]"
                      :src="whoWeWorkWithImageUrl(idx)"
                      :alt="label"
                      class="h-full w-full object-cover"
                    />
                    <div v-else-if="!form['who_image_' + idx]" class="flex h-full w-full items-center justify-center text-xs text-zinc-400">No image</div>
                    <div v-else class="flex h-full w-full items-center justify-center text-xs text-amber-600">New selected</div>
                  </div>
                </div>
                <div class="min-w-0 flex-1 space-y-2">
                  <div>
                    <label :for="'who_desc_' + idx" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">Description (2 lines on card)</label>
                    <textarea
                      :id="'who_desc_' + idx"
                      v-model="form['who_description_' + idx]"
                      rows="2"
                      maxlength="300"
                      :class="inputClass"
                      class="mt-1"
                      placeholder="Short description for this card…"
                    />
                  </div>
                  <input
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm file:mr-2 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-zinc-900 file:hover:bg-amber-400"
                    @change="form['who_image_' + idx] = $event.target.files?.[0] ?? null"
                  />
                  <label v-if="whoWeWorkWithImageUrl(idx) || form['who_image_' + idx]" class="flex cursor-pointer items-center gap-2 text-sm text-red-600 dark:text-red-400">
                    <input v-model="form['who_remove_image_' + idx]" type="checkbox" class="rounded border-zinc-300" />
                    Remove image (use default icon)
                  </label>
                </div>
              </div>
            </div>
          </FormCard>

          <!-- Section image (Trust, What We Do) – homepage only -->
          <FormCard
            v-if="hasSectionImage"
            title="Section image (illustration)"
            description="Image shown in this section on the homepage. Replaces the default vector. Leave empty to keep the default."
          >
            <template #vector>
              <Image class="h-12 w-12" stroke-width="1.5" />
            </template>
            <div>
              <input
                name="section_image"
                type="file"
                accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                :class="inputClass"
                @change="form.section_image = $event.target.files?.[0] ?? null"
              />
              <div v-if="sectionImageUrl && !form.section_image" class="mt-3 flex flex-wrap items-center gap-4">
                <img :src="sectionImageUrl" alt="Current section" class="max-h-32 rounded-lg border border-zinc-200 object-contain dark:border-zinc-600" />
                <label class="flex cursor-pointer items-center gap-2 text-sm text-red-600 dark:text-red-400">
                  <input v-model="form.remove_section_image" type="checkbox" class="rounded border-zinc-300" />
                  Remove image (use default vector)
                </label>
              </div>
              <p v-if="form.section_image" class="mt-2 text-xs text-amber-600 dark:text-amber-400">New image selected (save to apply)</p>
              <p v-if="sectionErrors.section_image" class="mt-2 text-sm text-red-500">{{ Array.isArray(sectionErrors.section_image) ? sectionErrors.section_image[0] : sectionErrors.section_image }}</p>
            </div>
          </FormCard>
        </template>

        <FormCard
          title="Order & visibility"
          description="Control where this section appears and whether visitors can see it."
        >
          <template #vector>
            <ListOrdered class="h-12 w-12" stroke-width="1.5" />
          </template>
          <div class="space-y-5">
            <div>
              <label for="sort_order" :class="labelClass">Sort order</label>
              <input
                id="sort_order"
                v-model.number="form.sort_order"
                type="number"
                min="0"
                :class="inputClass"
              />
              <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                Lower numbers appear first (e.g. 0 = first, 1 = second). Use this to reorder sections on the homepage.
              </p>
            </div>
            <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700 dark:bg-zinc-800/30">
              <input
                id="visible"
                v-model="form.visible"
                type="checkbox"
                class="mt-1 h-4 w-4 rounded border-zinc-300 text-amber-500 focus:ring-amber-500"
              />
              <div>
                <label for="visible" class="flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                  <Eye class="h-4 w-4" stroke-width="2" />
                  Visible on homepage
                </label>
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                  When unchecked, this section is hidden from the live site but kept in the database.
                </p>
              </div>
            </div>
          </div>
        </FormCard>

        <div class="flex flex-wrap gap-3">
          <button
            type="submit"
            form="homepage-section-form"
            :disabled="form.processing"
            class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-amber-400 disabled:opacity-70"
          >
            {{ form.processing ? 'Saving…' : 'Save section' }}
          </button>
          <Link
            :href="route('admin.homepage.index')"
            class="rounded-xl border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
