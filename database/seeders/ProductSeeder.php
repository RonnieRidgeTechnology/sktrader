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
            ['category' => '3-seater-sofas', 'name' => 'Classic 3-Seater Fabric Sofa', 'short' => 'Comfortable 3-seater fabric sofa for your living room.', 'description' => 'A timeless 3-seater sofa in durable fabric. Ideal for living rooms. Available in multiple colours. Quality construction for lasting comfort.'],
            ['category' => '3-seater-sofas', 'name' => 'Modern 3-Seater Leather Sofa', 'short' => 'Elegant leather 3-seater for a premium look.', 'description' => 'Premium leather 3-seater sofa. Easy to clean and built to last. Perfect for homes and offices in Zambia.'],
            ['category' => '2-seater-sofas', 'name' => 'Compact 2-Seater Sofa', 'short' => 'Space-saving 2-seater for smaller rooms.', 'description' => 'Ideal for apartments and smaller living spaces. Sturdy frame and comfortable seating.'],
            ['category' => 'l-shaped-sofas', 'name' => 'L-Shaped Sectional Sofa', 'short' => 'Spacious L-shaped sofa for large living areas.', 'description' => 'Large L-shaped sectional. Plenty of seating for family and guests. Fabric or leather options.'],
            ['category' => 'corner-sofas', 'name' => 'Corner Sofa with Chaise', 'short' => 'Corner sofa with chaise for maximum comfort.', 'description' => 'Corner sofa with integrated chaise. Perfect for relaxing. Available in various fabrics.'],
            ['category' => 'fabric-sofas', 'name' => 'Soft Fabric 3-Seater', 'short' => 'Soft, family-friendly fabric sofa.', 'description' => 'Family-friendly fabric sofa. Easy to maintain and comfortable for daily use.'],
            ['category' => 'leather-sofas', 'name' => 'Executive Leather Sofa', 'short' => 'Executive-style leather sofa for home or office.', 'description' => 'Executive leather sofa. Adds a professional touch to your home or office. Durable and stylish.'],
            ['category' => 'armchairs', 'name' => 'Accent Armchair', 'short' => 'Stylish armchair for living room or study.', 'description' => 'Single accent armchair. Pairs well with sofas or as a reading chair. Multiple colours.'],
            ['category' => 'coffee-tables', 'name' => 'Solid Wood Coffee Table', 'short' => 'Sturdy wooden coffee table for your lounge.', 'description' => 'Solid wood coffee table. Practical and stylish. Fits most living room layouts.'],
            ['category' => 'beds', 'name' => 'Double Bed with Headboard', 'short' => 'Comfortable double bed with padded headboard.', 'description' => 'Double bed with upholstered headboard. Strong frame. Available in different finishes.'],
            ['category' => 'dining-tables', 'name' => '6-Seater Dining Table', 'short' => 'Dining table seating six.', 'description' => 'Sturdy 6-seater dining table. Ideal for family meals. Wood or laminate top.'],
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
