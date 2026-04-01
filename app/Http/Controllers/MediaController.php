<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serve files from public/media/ (product images, uploads).
 * Uses the 'media' disk (root = public_path('media')). No /storage symlink.
 * Safe path handling so images load reliably.
 */
class MediaController extends Controller
{
    /**
     * Serve a file from the media disk (public/media/). Path must be relative (e.g. products/foo.jpg).
     * If not found there, tries the public disk (storage/app/public) so legacy uploads still work.
     * URLs stay as /media/... (no /storage in the URL).
     */
    public function serve(Request $request, string $path): BinaryFileResponse|Response
    {
        $path = $this->normalizePath($path);
        if ($path === null || $path === '') {
            return $this->notFound();
        }

        $fullPath = null;
        $disk = Storage::disk('media');
        if ($disk->exists($path)) {
            $fullPath = $disk->path($path);
        } else {
            $publicDisk = Storage::disk('public');
            if ($publicDisk->exists($path)) {
                $fullPath = $publicDisk->path($path);
            }
        }

        if ($fullPath === null || ! is_file($fullPath) || ! is_readable($fullPath)) {
            return $this->notFound();
        }

        $mimeType = $this->mimeTypeFromPath($path);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function normalizePath(string $path): ?string
    {
        $path = trim((string) rawurldecode($path));
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path);
        $path = trim($path, '/');
        if ($path === '') {
            return null;
        }
        if (str_contains($path, '..')) {
            return null;
        }
        return $path;
    }

    private function mimeTypeFromPath(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
        ];

        return $map[$ext] ?? 'application/octet-stream';
    }

    private function notFound(): Response
    {
        return response('', 404)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
