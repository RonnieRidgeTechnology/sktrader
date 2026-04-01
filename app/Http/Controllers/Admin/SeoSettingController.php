<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeoSettingController extends Controller
{
    public function index()
    {
        $settings = SeoSetting::orderBy('page_key')->get();
        return Inertia::render('Admin/Seo/Index', ['settings' => $settings]);
    }

    public function edit(SeoSetting $seo)
    {
        return Inertia::render('Admin/Seo/Form', ['setting' => $seo]);
    }

    public function update(Request $request, SeoSetting $seo)
    {
        $validated = $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|string|max:500',
            'og_image'         => 'nullable|string|max:500',
            'og_type'          => 'nullable|string|in:website,article|max:32',
            'noindex'          => 'nullable|boolean',
        ]);
        $validated['noindex'] = (bool) ($validated['noindex'] ?? false);
        if ($request->hasFile('og_image_file')) {
            $validated['og_image'] = $request->file('og_image_file')->store('seo', 'media');
        }
        $seo->update($validated);
        return redirect()->route('admin.seo.index')->with('success', 'SEO settings updated.');
    }
}
