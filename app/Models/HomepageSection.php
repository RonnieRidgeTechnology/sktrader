<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'data',
        'visible',
        'sort_order',
    ];

    protected $casts = [
        'data'    => 'array',
        'visible' => 'boolean',
    ];

    /**
     * Use section key for route binding so URLs are /admin/homepage/hero/edit not /admin/homepage/8/edit.
     */
    public function getRouteKeyName(): string
    {
        return 'key';
    }
}
