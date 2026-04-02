<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomepageSectionController extends Controller
{
    /** Keys that match the public home page sections (in display order). */
    private const PUBLIC_HOME_KEYS = [
        'hero',
        'spotlight',
        'journey_strip',
        'why_pillars',
        'reels_strip',
        'quote_block',
        'delivery_line',
        'final_cta',
    ];

    public function index()
    {
        $sections = HomepageSection::whereIn('key', self::PUBLIC_HOME_KEYS)
            ->orderBy('sort_order')
            ->orderBy('key')
            ->get();
        return Inertia::render('Admin/Homepage/Index', ['sections' => $sections]);
    }

    /**
     * Edit a section by key. If the section row does not exist (e.g. seeder not run), create it with defaults so the page loads.
     */
    public function edit(string $section)
    {
        if (! in_array($section, self::PUBLIC_HOME_KEYS, true)) {
            $sectionModel = HomepageSection::where('key', $section)->first();
            if (! $sectionModel) {
                abort(404);
            }
            return Inertia::render('Admin/Homepage/Form', ['section' => $sectionModel]);
        }
        $sectionModel = HomepageSection::firstOrCreate(
            ['key' => $section],
            $this->defaultsForSectionKey($section)
        );
        return Inertia::render('Admin/Homepage/Form', ['section' => $sectionModel]);
    }

    /**
     * Default attributes when creating a missing homepage section (matches HomepageSectionSeeder).
     */
    private function defaultsForSectionKey(string $key): array
    {
        $defaults = [
            'hero' => ['title' => 'Room hero & Bento', 'visible' => true, 'sort_order' => 10, 'data' => null],
            'spotlight' => [
                'title'  => 'Spotlight',
                'visible' => true,
                'sort_order' => 20,
                'data'    => [
                    'eyebrow' => 'In focus',
                    'heading' => 'Featured pick',
                    'body'    => 'A featured product can appear here when enabled. Mark products as featured in Admin → Products.',
                ],
            ],
            'journey_strip' => [
                'title'  => 'Journey strip',
                'visible' => true,
                'sort_order' => 30,
                'data'    => [
                    'eyebrow' => 'From discovery to delivery',
                    'steps'   => [
                        ['title' => 'Discover', 'line' => 'Browse curated watches, perfumes, and serums.'],
                        ['title' => 'Choose', 'line' => 'Pick your style and preferences.'],
                        ['title' => 'Checkout', 'line' => 'Confirm order and delivery details.'],
                        ['title' => 'Pack with care', 'line' => 'Premium handling and careful packing.'],
                        ['title' => 'Delivery', 'line' => 'Delivered with confidence.'],
                    ],
                ],
            ],
            'why_pillars' => [
                'title'  => 'Why us',
                'visible' => true,
                'sort_order' => 40,
                'data'    => [
                    'heading' => 'Curated luxury essentials, delivered with care.',
                    'pillars' => [
                        ['title' => 'Curated selection', 'text' => 'Focused picks across watches, perfumes, and serums — chosen for quality and premium presentation.'],
                        ['title' => 'Concierge support', 'text' => 'Quick help via WhatsApp — sizes, scent profiles, routine tips, and order updates.'],
                        ['title' => 'Careful delivery', 'text' => 'Careful packing and a reliable delivery flow so your order arrives protected.'],
                    ],
                ],
            ],
            'reels_strip' => ['title' => 'Video reels', 'visible' => true, 'sort_order' => 50, 'data' => ['eyebrow' => 'Reels', 'heading' => 'Videos are managed in Admin → Video Reels.']],
            'quote_block' => ['title' => 'Testimonials', 'visible' => true, 'sort_order' => 60, 'data' => ['eyebrow' => 'What people say', 'heading' => 'Testimonials are managed in Admin → Testimonials.']],
            'delivery_line' => [
                'title'  => 'Delivery line',
                'visible' => true,
                'sort_order' => 70,
                'data'    => [
                    'eyebrow'  => 'Delivery',
                    'headline' => 'Carefully packed. Delivered with confidence.',
                    'regions'  => ['Lusaka', 'Copperbelt', 'Southern', 'Central', 'Northern', 'Nationwide'],
                    'footer'   => 'Contact us for delivery details and lead times.',
                ],
            ],
            'final_cta' => [
                'title'  => 'Final CTA',
                'visible' => true,
                'sort_order' => 80,
                'data'    => [
                    'headline'         => 'Ready to find your next signature?',
                    'primary_button'   => 'Chat on WhatsApp',
                    'secondary_button' => 'Contact us',
                ],
            ],
        ];
        return $defaults[$key] ?? ['title' => ucfirst(str_replace('_', ' ', $key)), 'visible' => true, 'sort_order' => 0, 'data' => []];
    }

    /**
     * Debug: return hero section data and whether storage files exist (GET /admin/homepage/debug-hero).
     */
    public function debugHero()
    {
        $section = HomepageSection::where('key', 'hero')->first();
        if (! $section) {
            return response()->json(['error' => 'Hero section not found', 'hint' => 'Run HomepageSectionSeeder']);
        }
        $data = $section->data ?? [];
        $normalize = fn ($p) => is_string($p) && trim($p) !== '' ? str_replace('\\', '/', trim($p)) : null;
        $left = $normalize($data['image_left'] ?? null);
        $right = $normalize($data['image_right'] ?? null);
        $gallery = array_values(array_filter(array_map($normalize, $data['gallery_images'] ?? [])));
        $disk = 'media';
        return response()->json([
            'section_id'    => $section->id,
            'image_left'    => $left,
            'image_left_exists' => $left ? Storage::disk($disk)->exists($left) : false,
            'image_right'   => $right,
            'image_right_exists' => $right ? Storage::disk($disk)->exists($right) : false,
            'gallery_count' => count($gallery),
            'gallery'       => array_map(fn ($p) => ['path' => $p, 'exists' => Storage::disk($disk)->exists($p)], $gallery),
            'media_path'    => Storage::disk($disk)->path(''),
        ]);
    }

    public function update(Request $request, HomepageSection $section)
    {
        if ($section->key === 'hero') {
            $normalizePath = fn ($p) => is_string($p) && $p !== '' ? str_replace('\\', '/', trim($p)) : null;
            $request->validate([
                'headline'            => 'nullable|string|max:500',
                'subheading'         => 'nullable|string|max:255',
                'body'               => 'nullable|string|max:1000',
                'tagline'            => 'nullable|string|max:255',
                'cta_primary'        => 'nullable|string|max:100',
                'cta_secondary'      => 'nullable|string|max:100',
                'visible'            => 'boolean',
                'sort_order'         => 'integer|min:0',
                'image_left'         => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
                'image_right'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
                'remove_image_left'  => 'nullable|boolean',
                'remove_image_right' => 'nullable|boolean',
                'remove_gallery'     => 'nullable|array',
                'remove_gallery.*'   => 'integer|min:0',
            ]);
            $rules = [];
            for ($i = 0; $i <= 19; $i++) {
                $rules['new_gallery_' . $i] = 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120';
            }
            $request->validate($rules);
            $data = $section->data ?? [];
            $data['headline'] = $request->input('headline') ?: 'Luxury essentials, curated.';
            $data['subheading'] = $request->input('subheading') ?: 'Watches, perfumes, and skin care serums — premium picks, delivered with care.';
            $data['body'] = $request->input('body') ?: '';
            $data['tagline'] = $request->input('tagline') ?: '';
            $data['cta_primary'] = $request->input('cta_primary') ?: '';
            $data['cta_secondary'] = $request->input('cta_secondary') ?: '';
            $media = 'media'; // store in public/media/ (no symlink)
            if (! Storage::disk($media)->exists('hero')) {
                Storage::disk($media)->makeDirectory('hero');
            }
            if (! Storage::disk($media)->exists('hero/gallery')) {
                Storage::disk($media)->makeDirectory('hero/gallery');
            }
            if ($request->boolean('remove_image_left') && ! empty($data['image_left'])) {
                $path = $normalizePath($data['image_left']);
                if ($path && Storage::disk($media)->exists($path)) {
                    Storage::disk($media)->delete($path);
                }
                $data['image_left'] = null;
            }
            if ($request->hasFile('image_left')) {
                $oldPath = $normalizePath($data['image_left'] ?? null);
                if ($oldPath && Storage::disk($media)->exists($oldPath)) {
                    Storage::disk($media)->delete($oldPath);
                }
                $data['image_left'] = str_replace('\\', '/', $request->file('image_left')->store('hero', $media));
            }
            if ($request->boolean('remove_image_right') && ! empty($data['image_right'])) {
                $path = $normalizePath($data['image_right']);
                if ($path && Storage::disk($media)->exists($path)) {
                    Storage::disk($media)->delete($path);
                }
                $data['image_right'] = null;
            }
            if ($request->hasFile('image_right')) {
                $oldPath = $normalizePath($data['image_right'] ?? null);
                if ($oldPath && Storage::disk($media)->exists($oldPath)) {
                    Storage::disk($media)->delete($oldPath);
                }
                $data['image_right'] = str_replace('\\', '/', $request->file('image_right')->store('hero', $media));
            }
            $existing = array_values($data['gallery_images'] ?? []);
            $count = count($existing);
            for ($i = 0; $i < $count; $i++) {
                $key = 'replace_gallery_' . $i;
                if ($request->hasFile($key)) {
                    $oldPath = $normalizePath($existing[$i] ?? null);
                    if ($oldPath && Storage::disk($media)->exists($oldPath)) {
                        Storage::disk($media)->delete($oldPath);
                    }
                    $existing[$i] = str_replace('\\', '/', $request->file($key)->store('hero/gallery', $media));
                }
            }
            $removeIndices = array_unique(array_map('intval', $request->input('remove_gallery', [])));
            rsort($removeIndices);
            foreach ($removeIndices as $idx) {
                $oldPath = $normalizePath($existing[$idx] ?? null);
                if ($oldPath && Storage::disk($media)->exists($oldPath)) {
                    Storage::disk($media)->delete($oldPath);
                }
                array_splice($existing, $idx, 1);
            }
            for ($i = 0; $i <= 19; $i++) {
                $key = 'new_gallery_' . $i;
                if ($request->hasFile($key)) {
                    $existing[] = str_replace('\\', '/', $request->file($key)->store('hero/gallery', $media));
                }
            }
            $data['gallery_images'] = array_values($existing);
            $section->update([
                'data'       => $data,
                'visible'    => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            $msg = 'Section updated.';
            if ($request->hasFile('image_left')) {
                $msg .= ' Left image saved.';
            }
            if ($request->hasFile('image_right')) {
                $msg .= ' Right image saved.';
            }
            $newGallery = 0;
            for ($i = 0; $i <= 19; $i++) {
                if ($request->hasFile('new_gallery_' . $i)) {
                    $newGallery++;
                }
            }
            if ($newGallery > 0) {
                $msg .= ' ' . $newGallery . ' gallery image(s) added.';
            }
            return redirect()->route('admin.homepage.index')->with('success', $msg);
        }

        if ($section->key === 'spotlight') {
            $request->validate([
                'eyebrow' => 'nullable|string|max:120',
                'heading' => 'nullable|string|max:255',
                'body'    => 'nullable|string|max:500',
                'visible' => 'boolean',
                'sort_order' => 'integer|min:0',
                'spotlight_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'remove_spotlight_image' => 'nullable|boolean',
            ]);
            $data = array_merge($section->data ?? [], [
                'eyebrow' => $request->input('eyebrow'),
                'heading' => $request->input('heading'),
                'body'    => $request->input('body'),
            ]);
            $media = 'media';
            $normalizePath = fn ($p) => is_string($p) && $p !== '' ? str_replace('\\', '/', trim($p)) : null;
            if ($request->boolean('remove_spotlight_image') && ! empty($data['image'])) {
                $path = $normalizePath($data['image']);
                if ($path && Storage::disk($media)->exists($path)) {
                    Storage::disk($media)->delete($path);
                }
                $data['image'] = null;
            }
            if ($request->hasFile('spotlight_image')) {
                if (! Storage::disk($media)->exists('homepage')) {
                    Storage::disk($media)->makeDirectory('homepage');
                }
                $oldPath = $normalizePath($data['image'] ?? null);
                if ($oldPath && Storage::disk($media)->exists($oldPath)) {
                    Storage::disk($media)->delete($oldPath);
                }
                $data['image'] = str_replace('\\', '/', $request->file('spotlight_image')->store('homepage', $media));
            }
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if ($section->key === 'journey_strip') {
            $request->validate([
                'eyebrow' => 'nullable|string|max:120',
                'visible' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
            $steps = [];
            for ($i = 0; $i < 5; $i++) {
                $request->validate([
                    'step_title_' . $i => 'nullable|string|max:100',
                    'step_line_' . $i => 'nullable|string|max:200',
                ]);
                $steps[] = [
                    'title' => $request->input('step_title_' . $i, ''),
                    'line'  => $request->input('step_line_' . $i, ''),
                ];
            }
            $data = array_merge($section->data ?? [], [
                'eyebrow' => $request->input('eyebrow'),
                'steps'   => $steps,
            ]);
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if ($section->key === 'why_pillars') {
            $request->validate([
                'heading' => 'nullable|string|max:255',
                'visible' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
            $pillars = [];
            for ($i = 0; $i < 3; $i++) {
                $request->validate([
                    'pillar_title_' . $i => 'nullable|string|max:100',
                    'pillar_text_' . $i => 'nullable|string|max:500',
                ]);
                $pillars[] = [
                    'title' => $request->input('pillar_title_' . $i, ''),
                    'text'  => $request->input('pillar_text_' . $i, ''),
                ];
            }
            $data = array_merge($section->data ?? [], [
                'heading' => $request->input('heading'),
                'pillars' => $pillars,
            ]);
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if (in_array($section->key, ['reels_strip', 'quote_block'], true)) {
            $request->validate([
                'eyebrow' => 'nullable|string|max:120',
                'visible' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
            $data = array_merge($section->data ?? [], [
                'eyebrow' => $request->input('eyebrow'),
            ]);
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if ($section->key === 'delivery_line') {
            $request->validate([
                'eyebrow'           => 'nullable|string|max:120',
                'delivery_headline' => 'nullable|string|max:255',
                'footer'            => 'nullable|string|max:300',
                'regions'           => 'nullable|string|max:1000',
                'visible'           => 'boolean',
                'sort_order'        => 'integer|min:0',
            ]);
            $regionsStr = $request->input('regions', '');
            $regions = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $regionsStr))));
            if (empty($regions)) {
                $regions = $section->data['regions'] ?? ['Lusaka', 'Copperbelt', 'Southern', 'Central', 'Northern', 'Nationwide'];
            }
            $data = array_merge($section->data ?? [], [
                'eyebrow'  => $request->input('eyebrow'),
                'headline' => $request->input('delivery_headline'),
                'footer'   => $request->input('footer'),
                'regions'  => $regions,
            ]);
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if ($section->key === 'final_cta') {
            $request->validate([
                'final_headline'    => 'nullable|string|max:255',
                'primary_button'    => 'nullable|string|max:80',
                'secondary_button'  => 'nullable|string|max:80',
                'visible'           => 'boolean',
                'sort_order'        => 'integer|min:0',
            ]);
            $data = array_merge($section->data ?? [], [
                'headline'           => $request->input('final_headline'),
                'primary_button'     => $request->input('primary_button'),
                'secondary_button'   => $request->input('secondary_button'),
            ]);
            $section->update([
                'data' => $data,
                'visible' => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
        }

        if ($section->key === 'who_we_work_with') {
            $request->validate([
                'visible'    => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
            for ($i = 0; $i <= 5; $i++) {
                $request->validate([
                    'who_image_' . $i       => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
                    'who_description_' . $i  => 'nullable|string|max:300',
                ]);
            }
            $data = $section->data ?? [];
            $items = $data['items'] ?? [
                ['label' => 'Watch collectors', 'image' => null],
                ['label' => 'Fragrance lovers', 'image' => null],
                ['label' => 'Skincare routines', 'image' => null],
                ['label' => 'Gift buyers', 'image' => null],
                ['label' => 'Retail partners', 'image' => null],
                ['label' => 'Wholesale & corporate', 'image' => null],
            ];
            $labels = ['Watch collectors', 'Fragrance lovers', 'Skincare routines', 'Gift buyers', 'Retail partners', 'Wholesale & corporate'];
            for ($i = 0; $i < 6; $i++) {
                if (! isset($items[$i]) || ! is_array($items[$i])) {
                    $items[$i] = ['label' => $labels[$i] ?? 'Item ' . ($i + 1), 'image' => null];
                }
                $removeKey = 'who_remove_image_' . $i;
                if ($request->boolean($removeKey) && ! empty($items[$i]['image'])) {
                    if (Storage::disk('media')->exists($items[$i]['image'])) {
                        Storage::disk('media')->delete($items[$i]['image']);
                    }
                    $items[$i]['image'] = null;
                }
                $fileKey = 'who_image_' . $i;
                if ($request->hasFile($fileKey)) {
                    if (! empty($items[$i]['image']) && Storage::disk('media')->exists($items[$i]['image'])) {
                        Storage::disk('media')->delete($items[$i]['image']);
                    }
                    $items[$i]['image'] = str_replace('\\', '/', $request->file($fileKey)->store('homepage/who-we-work-with', 'media'));
                }
                $items[$i]['label'] = $items[$i]['label'] ?? $labels[$i];
                $items[$i]['description'] = $request->input('who_description_' . $i, $items[$i]['description'] ?? '');
            }
            $data['items'] = array_values($items);
            $section->update([
                'data'       => $data,
                'visible'    => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
        } elseif (in_array($section->key, ['trust', 'what_we_do'], true)) {
            $request->validate([
                'title'           => 'nullable|string|max:255',
                'content'         => 'nullable|string',
                'visible'         => 'boolean',
                'sort_order'      => 'integer|min:0',
                'section_image'   => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
                'remove_section_image' => 'nullable|boolean',
            ]);
            $data = $section->data ?? [];
            if ($request->boolean('remove_section_image') && ! empty($data['section_image'])) {
                if (Storage::disk('media')->exists($data['section_image'])) {
                    Storage::disk('media')->delete($data['section_image']);
                }
                $data['section_image'] = null;
            }
            if ($request->hasFile('section_image')) {
                if (! empty($data['section_image']) && Storage::disk('media')->exists($data['section_image'])) {
                    Storage::disk('media')->delete($data['section_image']);
                }
                $data['section_image'] = str_replace('\\', '/', $request->file('section_image')->store('homepage/sections', 'media'));
            }
            $section->update([
                'title'      => $request->input('title', $section->title),
                'content'    => $request->input('content', $section->content),
                'data'       => $data,
                'visible'    => $request->boolean('visible', true),
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
        } else {
            $validated = $request->validate([
                'title'      => 'nullable|string|max:255',
                'content'    => 'nullable|string',
                'data'       => 'nullable|array',
                'visible'    => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);
            $validated['visible'] = $request->boolean('visible', true);
            $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
            $section->update($validated);
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Section updated.');
    }
}
