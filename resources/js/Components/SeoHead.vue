<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
  /** Page title. If empty, uses global default from Admin → Settings → SEO. */
  title: { type: String, default: '' },
  /** Page description. If empty, uses global default. */
  description: { type: String, default: '' },
  /** Absolute canonical URL. If not set, uses current full URL (appUrl + path). */
  canonical: { type: String, default: '' },
  /** Absolute URL for og:image and twitter:image. Falls back to seoDefaultOgImage. */
  image: { type: String, default: '' },
  /** og:type - website or article */
  type: { type: String, default: 'website' },
  noindex: { type: Boolean, default: false },
  /** Optional comma-separated or array of keywords for meta name="keywords". */
  keywords: { type: [String, Array], default: '' },
});

const page = usePage();
const appUrl = page.props.appUrl || (typeof window !== 'undefined' ? window.location.origin : '');
const siteName = page.props.zuaaz?.name || 'SK Traders';
const defaultImage = page.props.seoDefaultOgImage || `${appUrl}/images/logo.png`;
const defaultTitle = page.props.seoDefaultMetaTitle || siteName;
const defaultDescription = page.props.seoDefaultMetaDescription || '';

const canonicalUrl = computed(() => {
  if (props.canonical) return props.canonical;
  if (typeof window !== 'undefined') return window.location.href.split('?')[0];
  return appUrl + (page.url || '');
});

const ogImage = computed(() => {
  const url = props.image || defaultImage;
  return url.startsWith('http') ? url : `${appUrl}${url.startsWith('/') ? '' : '/'}${url}`;
});

const resolvedTitle = computed(() => {
  const raw = (props.title && String(props.title).trim()) || defaultTitle;
  return raw ? String(raw).slice(0, 70) : defaultTitle;
});

const fullTitle = computed(() => {
  const t = resolvedTitle.value;
  return t.includes('|') ? t : `${t} | ${siteName}`;
});

const resolvedDescription = computed(() => {
  const raw = (props.description && String(props.description).trim()) || defaultDescription;
  return raw ? String(raw).slice(0, 160) : '';
});

const keywordsValue = computed(() => {
  if (!props.keywords) return '';
  return Array.isArray(props.keywords) ? props.keywords.join(', ') : String(props.keywords).trim();
});
</script>

<template>
  <Head :title="fullTitle">
    <meta v-if="resolvedDescription" name="description" :content="resolvedDescription" />
    <meta v-if="keywordsValue" name="keywords" :content="keywordsValue" />
    <meta v-if="noindex" name="robots" content="noindex, nofollow" />
    <link v-if="canonicalUrl" rel="canonical" :href="canonicalUrl" />

    <!-- Open Graph -->
    <meta property="og:type" :content="type" />
    <meta property="og:title" :content="fullTitle" />
    <meta v-if="resolvedDescription" property="og:description" :content="resolvedDescription" />
    <meta property="og:url" :content="canonicalUrl" />
    <meta property="og:image" :content="ogImage" />
    <meta property="og:site_name" :content="siteName" />
    <meta property="og:locale" content="en_US" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="fullTitle" />
    <meta v-if="resolvedDescription" name="twitter:description" :content="resolvedDescription" />
    <meta name="twitter:image" :content="ogImage" />

  </Head>
</template>
