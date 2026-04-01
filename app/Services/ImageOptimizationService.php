<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /** Max width for resized images */
    private const MAX_WIDTH = 1200;

    /** JPEG quality (1-100) */
    private const QUALITY = 85;

    /**
     * Store and optionally resize image. Uses GD if available.
     *
     * @return string|null Path stored in disk (e.g. "products/xyz.jpg") or null on failure
     */
    public function storeAndOptimize(UploadedFile $file, string $directory = 'uploads'): ?string
    {
        $path = $file->store($directory, 'media');
        if (! $path) {
            return null;
        }

        $fullPath = Storage::disk('media')->path($path);
        $this->optimizeFile($fullPath);

        return $path;
    }

    /**
     * Resize/compress image in place if GD is available.
     */
    public function optimizeFile(string $fullPath): void
    {
        if (! extension_loaded('gd')) {
            return;
        }

        $info = @getimagesize($fullPath);
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            return;
        }

        $width = (int) $info[0];
        $height = (int) $info[1];
        if ($width <= self::MAX_WIDTH) {
            return;
        }

        $newWidth = self::MAX_WIDTH;
        $newHeight = (int) round($height * (self::MAX_WIDTH / $width));

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
            IMAGETYPE_PNG => @imagecreatefrompng($fullPath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : null,
            default => null,
        };

        if (! $src) {
            return;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if (! $dst) {
            imagedestroy($src);
            return;
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        $saved = false;
        if ($info[2] === IMAGETYPE_JPEG) {
            $saved = imagejpeg($dst, $fullPath, self::QUALITY);
        } elseif ($info[2] === IMAGETYPE_PNG) {
            $saved = imagepng($dst, $fullPath, (int) round(9 - (self::QUALITY / 100) * 9));
        } elseif ($info[2] === IMAGETYPE_WEBP && function_exists('imagewebp')) {
            $saved = imagewebp($dst, $fullPath, self::QUALITY);
        }
        imagedestroy($dst);

        if (! $saved) {
            // Leave original as-is
        }
    }
}
