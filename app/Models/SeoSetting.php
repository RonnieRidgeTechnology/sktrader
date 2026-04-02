<?php

namespace App\Models;

use App\Support\PublicSiteCache;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_image',
        'og_type',
        'noindex',
    ];

    protected $casts = [
        'noindex' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => PublicSiteCache::forgetSeoByPage());
        static::deleted(fn () => PublicSiteCache::forgetSeoByPage());
    }
}
