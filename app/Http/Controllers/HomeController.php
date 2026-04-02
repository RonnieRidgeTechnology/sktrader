<?php

namespace App\Http\Controllers;

use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\VideoReel;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $videoReels = collect();
        if (class_exists(VideoReel::class)) {
            $videoReels = VideoReel::query()
                ->where('status', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($r) => ['title' => $r->title, 'videoUrl' => $r->video_url]);
        }

        $testimonials = collect();
        if (class_exists(Testimonial::class)) {
            $testimonials = Testimonial::query()
                ->where('status', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['quote', 'name', 'role', 'company']);
        }

        $normalizePath = static function ($path) {
            if (empty($path) || ! is_string($path)) {
                return null;
            }
            $path = str_replace('\\', '/', trim($path));
            $path = preg_replace('#^/+(storage/|public/|media/)?#i', '', $path);

            return $path !== '' ? $path : null;
        };

        $sectionImageTrust = null;
        $sectionImageServices = null;
        $heroData = [];
        $homeSections = [];

        if (class_exists(HomepageSection::class)) {
            $keys = ['hero', 'trust', 'what_we_do', 'spotlight', 'journey_strip', 'why_pillars', 'delivery_line', 'final_cta'];
            $sections = HomepageSection::query()
                ->whereIn('key', $keys)
                ->get()
                ->keyBy('key');

            $hero = $sections->get('hero');
            $heroData = $hero && is_array($hero->data) ? $hero->data : [];

            $trustSection = $sections->get('trust');
            if ($trustSection && ! empty($trustSection->data['section_image'])) {
                $p = $normalizePath($trustSection->data['section_image']);
                if ($p) {
                    $sectionImageTrust = '/media/' . $p;
                }
            }

            $whatWeDoSection = $sections->get('what_we_do');
            if ($whatWeDoSection && ! empty($whatWeDoSection->data['section_image'])) {
                $p = $normalizePath($whatWeDoSection->data['section_image']);
                if ($p) {
                    $sectionImageServices = '/media/' . $p;
                }
            }

            foreach (['spotlight', 'journey_strip', 'why_pillars', 'delivery_line', 'final_cta'] as $key) {
                $sec = $sections->get($key);
                if ($sec && $sec->visible && is_array($sec->data)) {
                    $homeSections[$key] = $sec->data;
                    if ($key === 'spotlight' && ! empty($sec->data['image'])) {
                        $path = preg_replace('#^/?(storage/|public/|media/)?#', '', trim(str_replace('\\', '/', $sec->data['image'])));
                        if ($path !== '') {
                            $homeSections[$key]['image_url'] = '/media/' . $path;
                        }
                    }
                }
            }
        }

        $heroSliderImages = [];
        foreach (['image_left', 'image_right'] as $key) {
            $p = $normalizePath($heroData[$key] ?? null);
            if ($p) {
                $heroSliderImages[] = '/media/' . $p;
            }
        }
        foreach ($heroData['gallery_images'] ?? [] as $path) {
            $norm = $normalizePath($path);
            if ($norm) {
                $heroSliderImages[] = '/media/' . $norm;
            }
        }

        $heroGalleryImages = [];
        foreach ($heroData['gallery_images'] ?? [] as $path) {
            $norm = $normalizePath($path);
            if ($norm) {
                $heroGalleryImages[] = '/media/' . $norm;
            }
        }

        $featuredProducts = collect();
        if (class_exists(Product::class)) {
            $featuredProducts = Product::query()
                ->with('category:id,name,slug,parent_id', 'category.parent:id,name')
                ->where('status', true)
                ->where('is_featured', true)
                ->orderBy('name')
                ->get(['id', 'category_id', 'name', 'slug', 'short_description', 'image', 'price'])
                ->map(function ($product) {
                    $path = $product->image ? preg_replace('#^/?(storage/|public/|media/)?#', '', trim(str_replace('\\', '/', $product->image))) : null;
                    $arr = $product->toArray();
                    $arr['image_url'] = $path ? '/media/' . $path : null;

                    return $arr;
                });
        }

        return Inertia::render('Home', [
            'title' => 'Home',
            'videoReels' => $videoReels,
            'testimonials' => $testimonials,
            'heroHeadline' => $heroData['headline'] ?? 'Luxury essentials, curated.',
            'heroSubheading' => $heroData['subheading'] ?? 'Watches, perfumes, and skin care serums — premium picks, delivered with care.',
            'heroSliderImages' => $heroSliderImages,
            'heroGalleryImages' => $heroGalleryImages,
            'sectionImageTrustUrl' => $sectionImageTrust,
            'sectionImageServicesUrl' => $sectionImageServices,
            'featuredProducts' => $featuredProducts,
            'homeSections' => $homeSections,
        ]);
    }
}
