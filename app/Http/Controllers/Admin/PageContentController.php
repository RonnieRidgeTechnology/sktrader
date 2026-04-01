<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PageContentController extends Controller
{
    public function index()
    {
        $config = config('page_content.pages', []);
        $saved = PageContent::whereIn('page_key', array_keys($config))->get()->keyBy('page_key');

        $pages = [];
        foreach ($config as $key => $pageConfig) {
            $pages[] = [
                'page_key' => $key,
                'name' => $pageConfig['name'],
                'route' => $pageConfig['route'] ?? $key,
                'has_content' => $saved->has($key),
                'updated_at' => $saved->get($key)?->updated_at?->toIso8601String(),
            ];
        }

        usort($pages, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

        return Inertia::render('Admin/PageContent/Index', [
            'pages' => $pages,
        ]);
    }

    public function edit(string $pageKey)
    {
        $config = config('page_content.pages', []);
        if (!isset($config[$pageKey])) {
            abort(404, 'Page not found');
        }

        $pageConfig = $config[$pageKey];
        $page = PageContent::firstOrCreate(
            ['page_key' => $pageKey],
            ['name' => $pageConfig['name'], 'content' => []]
        );

        // Always load fresh content from DB so the edit form shows current saved data
        $page->refresh();
        $content = $page->content;
        if (! is_array($content)) {
            $content = [];
        }

        return Inertia::render('Admin/PageContent/Edit', [
            'pageKey' => $pageKey,
            'pageName' => $pageConfig['name'],
            'fields' => $pageConfig['fields'] ?? [],
            'content' => $content,
        ]);
    }

    public function update(Request $request, string $pageKey)
    {
        $config = config('page_content.pages', []);
        if (!isset($config[$pageKey])) {
            abort(404, 'Page not found');
        }

        $page = PageContent::firstOrCreate(
            ['page_key' => $pageKey],
            ['name' => $config[$pageKey]['name'], 'content' => []]
        );

        $content = $request->input('content', []);
        if (is_string($content)) {
            $content = json_decode($content, true) ?: [];
        }
        if (!is_array($content)) {
            $content = [];
        }

        // Store uploaded images for About (hero_image, owner_image)
        if ($pageKey === 'about') {
            foreach (['hero_image', 'owner_image'] as $imageKey) {
                if ($request->hasFile($imageKey)) {
                    $request->validate([$imageKey => 'image|mimes:png,jpg,jpeg,webp|max:5120']);
                    $existing = $content[$imageKey] ?? null;
                    if ($existing && Storage::disk('media')->exists($existing)) {
                        Storage::disk('media')->delete($existing);
                    }
                    $content[$imageKey] = $request->file($imageKey)->store('page-content/about', 'media');
                }
                if (isset($content[$imageKey]) && (empty($content[$imageKey]) || $content[$imageKey] === '')) {
                    $current = $page->content ?? [];
                    $oldPath = $current[$imageKey] ?? null;
                    if ($oldPath && Storage::disk('media')->exists($oldPath)) {
                        Storage::disk('media')->delete($oldPath);
                    }
                }
            }
        }

        // Normalize steps: ensure bullets are arrays
        if (isset($content['steps']) && is_array($content['steps'])) {
            foreach ($content['steps'] as $i => $step) {
                if (isset($step['bullets']) && is_string($step['bullets'])) {
                    $content['steps'][$i]['bullets'] = array_filter(array_map('trim', explode("\n", $step['bullets'])));
                }
            }
        }

        $page->update(['content' => $content]);

        return redirect()->route('admin.page-content.edit', $pageKey)->with('success', 'Page content saved.');
    }
}
