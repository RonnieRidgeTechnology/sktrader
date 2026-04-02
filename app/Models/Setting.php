<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $all = self::getAll();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        self::clearCache();
    }

    public static function getMany(array $keys): array
    {
        $all = self::getAll();
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $all[$key] ?? null;
        }
        return $result;
    }

    public static function getAll(): array
    {
        return Cache::remember('app_settings', 300, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }

    public static function getStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        $path = str_replace('\\', '/', trim($path));
        return Storage::disk('media')->exists($path) ? '/media/' . $path : null;
    }

    /**
     * Public URL under /media/ without hitting the filesystem (fast path for shared Inertia props).
     * Prefer getStorageUrl() when you must hide missing files (e.g. admin previews).
     */
    public static function publicMediaUrl(?string $path): ?string
    {
        if (! $path || ! is_string($path)) {
            return null;
        }
        $path = str_replace('\\', '/', trim($path));
        $path = preg_replace('#^/?(storage/|public/|media/)?#i', '', $path);
        if ($path === '') {
            return null;
        }

        return '/media/' . $path;
    }
}
