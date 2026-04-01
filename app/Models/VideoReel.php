<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoReel extends Model
{
    protected $fillable = ['title', 'file_path', 'sort_order', 'status'];

    protected $casts = [
        'status'    => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getVideoUrlAttribute(): string
    {
        return asset('media/video/' . ltrim($this->file_path, '/'));
    }
}
