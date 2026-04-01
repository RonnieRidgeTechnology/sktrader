<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            ['category' => 'classic-watches', 'name' => 'Classic Silver Watch', 'short' => 'A clean, timeless watch for everyday wear.', 'description' => 'A refined classic watch with a minimalist dial and a comfortable strap. Designed for daily wear and special occasions.'],
            ['category' => 'sport-watches', 'name' => 'Sport Chronograph Watch', 'short' => 'Bold chronograph styling with a sport-forward feel.', 'description' => 'A sport chronograph-inspired watch with a confident silhouette and durable materials. Great for an active lifestyle look.'],
            ['category' => 'luxury-watches', 'name' => 'Luxury Gold-Tone Watch', 'short' => 'A premium statement piece with polished details.', 'description' => 'A gold-tone luxury watch designed to elevate your look. Premium presentation and a clean, high-end finish.'],
            ['category' => 'fresh-perfumes', 'name' => 'Fresh Citrus Perfume', 'short' => 'Bright, clean, and uplifting — perfect for daily wear.', 'description' => 'A fresh citrus-forward fragrance with crisp, clean energy. Ideal for daytime wear and warm weather.'],
            ['category' => 'woody-perfumes', 'name' => 'Woody Amber Perfume', 'short' => 'Warm, deep, and elegant — an evening signature.', 'description' => 'A woody amber fragrance with depth and presence. Great for evenings, events, and confident everyday wear.'],
            ['category' => 'sweet-perfumes', 'name' => 'Sweet Vanilla Perfume', 'short' => 'Smooth sweetness with a cozy finish.', 'description' => 'A sweet vanilla-forward fragrance balanced for wearability. A great gifting pick with crowd-pleasing appeal.'],
            ['category' => 'hydration-serums', 'name' => 'Hydration Booster Serum', 'short' => 'Routine-friendly hydration for softer, smoother skin.', 'description' => 'A hydration-focused serum designed to support moisture and comfort. Perfect as a daily routine staple.'],
            ['category' => 'glow-serums', 'name' => 'Glow Radiance Serum', 'short' => 'A glow-focused serum for a fresher look.', 'description' => 'A glow-supporting serum to help your skin look brighter and more even over time.'],
            ['category' => 'gift-sets', 'name' => 'Gift Set: Perfume + Serum', 'short' => 'A curated duo — gift-ready and premium.', 'description' => 'A curated bundle combining a signature fragrance and a routine-friendly serum. Premium gifting made easy.'],
        ];

        foreach ($products as $p) {
            $category = $categories->get($p['category']) ?? $categories->first();
            if (! $category) {
                continue;
            }
            Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id'         => $category->id,
                    'name'                => $p['name'],
                    'short_description'   => $p['short'],
                    'description'         => $p['description'],
                    'image'               => null,
                    'gallery'             => [],
                    'status'              => true,
                ]
            );
        }
    }
}
