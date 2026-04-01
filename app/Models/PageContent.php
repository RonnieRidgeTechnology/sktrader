<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = ['page_key', 'name', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    public static function getForPage(string $pageKey): array
    {
        $row = self::where('page_key', $pageKey)->first();
        return $row ? ($row->content ?? []) : [];
    }
}
