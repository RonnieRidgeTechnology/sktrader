<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache keys for data shared on every public Inertia request. Invalidate from admin/models when content changes.
 */
class PublicSiteCache
{
    public static function navCategoriesKey(): string
    {
        return 'nav_categories_public.' . sha1(rtrim((string) config('app.url'), '/'));
    }

    public static function forgetNavCategories(): void
    {
        Cache::forget(self::navCategoriesKey());
    }

    public static function seoByPageKey(): string
    {
        return 'inertia_seo_by_page.' . sha1(rtrim((string) config('app.url'), '/'));
    }

    public static function forgetSeoByPage(): void
    {
        Cache::forget(self::seoByPageKey());
    }
}
