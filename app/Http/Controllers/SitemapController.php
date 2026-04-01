<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = rtrim(config('app.url'), '/');

        $urls = [
            ['loc' => $base . '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $base . '/about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/services', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/products', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base . '/private-label', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/how-it-works', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/why-choose-us', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/quality', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/faq', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base . '/contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base . '/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => $base . '/terms-and-conditions', 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => $base . '/manufacturing-policy', 'changefreq' => 'yearly', 'priority' => '0.4'],
        ];

        $categories = Category::where('status', true)->get(['slug', 'updated_at']);
        foreach ($categories as $category) {
            if (! $category->slug) continue;
            $urls[] = [
                'loc'        => $base . '/category/' . $category->slug,
                'changefreq' => 'weekly',
                'priority'   => '0.8',
                'lastmod'    => $category->updated_at?->toW3cString(),
            ];
        }

        $products = Product::where('status', true)->get(['slug', 'updated_at']);
        foreach ($products as $product) {
            $urls[] = [
                'loc'        => $base . '/products/' . $product->slug,
                'changefreq' => 'weekly',
                'priority'   => '0.7',
                'lastmod'    => $product->updated_at->toW3cString(),
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc']) . '</loc>' . "\n";
            $xml .= '    <changefreq>' . ($u['changefreq'] ?? 'weekly') . '</changefreq>' . "\n";
            $xml .= '    <priority>' . ($u['priority'] ?? '0.5') . '</priority>' . "\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type'        => 'application/xml; charset=UTF-8',
            'Cache-Control'       => 'public, max-age=3600',
        ]);
    }
}
