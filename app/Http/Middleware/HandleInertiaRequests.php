<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\SeoSetting;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $zuaaz = $this->zuaazFromConfig();
        if (Schema::hasTable('settings')) {
            $zuaaz = $this->mergeSettings($zuaaz);
        }

        $navCategories = [];
        if (Schema::hasTable('categories')) {
            $all = Category::where('status', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'parent_id']);
            $byParent = $all->groupBy('parent_id');
            $topLevel = $byParent->get(null, collect())->values();
            $flat = collect();
            foreach ($topLevel as $parent) {
                $flat->push($parent);
                $flat = $flat->merge($byParent->get($parent->id, collect()));
            }
            $navCategories = $flat->values()->all();
        }

        $appUrl = rtrim(config('app.url'), '/');
        $defaultOgImage = $zuaaz['og_image_url'] ?? $zuaaz['header_logo_url'] ?? $appUrl . '/images/logo.png';
        if ($defaultOgImage && !preg_match('#^https?://#', $defaultOgImage)) {
            $defaultOgImage = $appUrl . ($defaultOgImage[0] === '/' ? '' : '/') . $defaultOgImage;
        }

        $seoByPage = [];
        if (Schema::hasTable('seo_settings')) {
            $seoByPage = SeoSetting::all()->keyBy('page_key')->map(function (SeoSetting $s) use ($appUrl) {
                $ogImageUrl = null;
                if ($s->og_image && Storage::disk('media')->exists($s->og_image)) {
                    $ogImageUrl = $appUrl . '/media/' . ltrim(str_replace('\\', '/', $s->og_image), '/');
                }
                return [
                    'meta_title' => $s->meta_title,
                    'meta_description' => $s->meta_description,
                    'meta_keywords' => $s->meta_keywords,
                    'canonical_url' => $s->canonical_url,
                    'og_image' => $ogImageUrl,
                    'og_type' => $s->og_type ?? 'website',
                    'noindex' => (bool) $s->noindex,
                ];
            })->all();
        }

        $pageKey = $this->resolvePageKey($request);

        return [
            ...parent::share($request),
            'csrf_token' => csrf_token(),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
                'newsletter_success' => $request->session()->get('newsletter_success'),
            ],
            'zuaaz' => $zuaaz,
            'navCategories' => $navCategories,
            'appUrl' => $appUrl,
            'seoDefaultOgImage' => $defaultOgImage,
            'seoDefaultMetaTitle' => $zuaaz['meta_title_default'] ?? '',
            'seoDefaultMetaDescription' => $zuaaz['meta_description_default'] ?? '',
            'seoByPage' => $seoByPage,
            'pageKey' => $pageKey,
            'cartCount' => $request->hasSession() ? app(CartService::class)->getCount() : 0,
            'cartDrawer' => $request->hasSession() ? [
                'items' => app(CartService::class)->getItems(),
                'subtotal' => round(app(CartService::class)->getSubtotal(), 2),
            ] : ['items' => [], 'subtotal' => 0],
        ];
    }

    private function resolvePageKey(Request $request): ?string
    {
        $name = $request->route()?->getName();
        $map = [
            'home' => 'home',
            'about' => 'about',
            'services' => 'services',
            'products' => 'products',
            'contact' => 'contact',
            'faq' => 'faq',
            'why-choose-us' => 'why-choose-us',
            'how-it-works' => 'how-it-works',
            'quality' => 'quality',
            'private-label' => 'private-label',
            'privacy-policy' => 'privacy-policy',
            'manufacturing-policy' => 'manufacturing-policy',
            'terms-and-conditions' => 'terms-and-conditions',
        ];
        return $map[$name] ?? null;
    }

    private function zuaazFromConfig(): array
    {
        return [
            'name'     => config('zuaaz.name'),
            'tagline'  => config('zuaaz.tagline'),
            'whatsapp' => config('zuaaz.whatsapp'),
            'contact'  => config('zuaaz.contact'),
            'address'  => config('zuaaz.address'),
            'header_logo_url' => null,
            'footer_logo_url' => null,
            'favicon_url' => null,
            'primary_color' => null,
            'secondary_color' => null,
            'map_embed_code' => null,
        ];
    }

    private function mergeSettings(array $zuaaz): array
    {
        $all = Setting::getAll();
        if (!empty($all['site_name'])) {
            $zuaaz['name'] = $all['site_name'];
        }
        if (isset($all['tagline'])) {
            $zuaaz['tagline'] = $all['tagline'];
        }
        $zuaaz['whatsapp'] = [
            'primary'   => $all['whatsapp_primary'] ?? $zuaaz['whatsapp']['primary'] ?? '',
            'secondary' => $all['whatsapp_secondary'] ?? $zuaaz['whatsapp']['secondary'] ?? '',
            'enabled'   => ($all['whatsapp_enabled'] ?? '1') !== '0',
        ];
        $zuaaz['contact'] = [
            'email'     => $all['contact_email'] ?? $zuaaz['contact']['email'] ?? '',
            'website'   => $all['contact_website'] ?? $zuaaz['contact']['website'] ?? '',
            'phone'     => $all['contact_phone'] ?? '',
            'instagram' => $all['contact_instagram'] ?? $zuaaz['contact']['instagram'] ?? '',
            'facebook'  => $all['contact_facebook'] ?? $zuaaz['contact']['facebook'] ?? '',
            'linkedin'  => $all['contact_linkedin'] ?? $zuaaz['contact']['linkedin'] ?? '',
            'twitter'   => $all['contact_twitter'] ?? $zuaaz['contact']['twitter'] ?? '',
        ];
        $zuaaz['address'] = [
            'office'        => $all['address_office'] ?? $zuaaz['address']['office'] ?? '',
            'manufacturing' => $all['address_manufacturing'] ?? $zuaaz['address']['manufacturing'] ?? '',
        ];
        $zuaaz['header_logo_url'] = Setting::getStorageUrl($all['header_logo'] ?? null);
        $zuaaz['footer_logo_url'] = Setting::getStorageUrl($all['footer_logo'] ?? null);
        $zuaaz['favicon_url'] = Setting::getStorageUrl($all['favicon'] ?? null);
        $zuaaz['primary_color'] = !empty($all['primary_color']) ? $all['primary_color'] : null;
        $zuaaz['secondary_color'] = !empty($all['secondary_color']) ? $all['secondary_color'] : null;
        $zuaaz['map_embed_code'] = !empty($all['map_embed_code']) ? $all['map_embed_code'] : null;
        $zuaaz['footer_copyright'] = isset($all['footer_copyright']) ? (string) $all['footer_copyright'] : '';
        $zuaaz['hero_cta_primary'] = isset($all['hero_cta_primary']) ? (string) $all['hero_cta_primary'] : '';
        $zuaaz['hero_cta_secondary'] = isset($all['hero_cta_secondary']) ? (string) $all['hero_cta_secondary'] : '';
        $zuaaz['contact_form_title'] = isset($all['contact_form_title']) ? (string) $all['contact_form_title'] : '';
        $zuaaz['newsletter_enabled'] = ($all['newsletter_enabled'] ?? '1') !== '0';
        $zuaaz['meta_title_default'] = isset($all['meta_title_default']) ? (string) $all['meta_title_default'] : '';
        $zuaaz['meta_description_default'] = isset($all['meta_description_default']) ? (string) $all['meta_description_default'] : '';
        $zuaaz['og_image_url'] = Setting::getStorageUrl($all['og_image'] ?? null);
        return $zuaaz;
    }
}
