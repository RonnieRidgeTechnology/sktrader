import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * Resolve SEO props for the current page from admin SEO settings (seoByPage[pageKey]).
 * Falls back to passed defaults (title, description, canonical, image, keywords, noindex, type).
 * Use with SeoHead: <SeoHead v-bind="pageSeoProps" />
 *
 * @param {string} [pageKey] - Override page key (defaults to usePage().props.pageKey)
 * @param {Object|import('vue').Ref<Object>|import('vue').ComputedRef<Object>} [defaults] - Fallback values (can be reactive)
 * @returns {import('vue').ComputedRef<Object>} Props for SeoHead
 */
export function usePageSeo(pageKey = null, defaults = {}) {
  const page = usePage();
  const key = pageKey ?? page.props.pageKey ?? null;
  const seoByPage = page.props.seoByPage ?? {};
  const seo = key ? seoByPage[key] : null;

  return computed(() => {
    const d = typeof defaults === 'object' && defaults !== null && 'value' in defaults ? defaults.value : defaults;
    return {
      title: seo?.meta_title ?? d?.title ?? '',
      description: seo?.meta_description ?? d?.description ?? '',
      canonical: seo?.canonical_url ?? d?.canonical ?? '',
      image: seo?.og_image ?? d?.image ?? '',
      keywords: seo?.meta_keywords ?? d?.keywords ?? '',
      noindex: seo?.noindex ?? d?.noindex ?? false,
      type: seo?.og_type ?? d?.type ?? 'website',
    };
  });
}
