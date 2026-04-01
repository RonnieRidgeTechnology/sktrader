<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $sortOrder = 0;

        $tree = [
            [
                'name'     => 'Watches',
                'slug'     => 'watches',
                'children' => [
                    ['name' => 'Classic', 'slug' => 'classic-watches'],
                    ['name' => 'Sport', 'slug' => 'sport-watches'],
                    ['name' => 'Luxury', 'slug' => 'luxury-watches'],
                    ['name' => 'Minimal', 'slug' => 'minimal-watches'],
                ],
            ],
            [
                'name'     => 'Perfumes',
                'slug'     => 'perfumes',
                'children' => [
                    ['name' => 'Fresh', 'slug' => 'fresh-perfumes'],
                    ['name' => 'Woody', 'slug' => 'woody-perfumes'],
                    ['name' => 'Sweet', 'slug' => 'sweet-perfumes'],
                    ['name' => 'Spicy', 'slug' => 'spicy-perfumes'],
                ],
            ],
            [
                'name'     => 'Skin Care Serums',
                'slug'     => 'skin-care-serums',
                'children' => [
                    ['name' => 'Hydration', 'slug' => 'hydration-serums'],
                    ['name' => 'Glow', 'slug' => 'glow-serums'],
                    ['name' => 'Anti-Acne', 'slug' => 'anti-acne-serums'],
                    ['name' => 'Anti-Aging', 'slug' => 'anti-aging-serums'],
                ],
            ],
            ['name' => 'Gift Sets', 'slug' => 'gift-sets'],
        ];

        foreach ($tree as $item) {
            $parent = Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'parent_id'   => null,
                    'sort_order'  => $sortOrder++,
                    'name'        => $item['name'],
                    'description' => null,
                    'status'      => true,
                ]
            );

            if (! empty($item['children'])) {
                $childOrder = 0;
                foreach ($item['children'] as $child) {
                    Category::updateOrCreate(
                        ['slug' => $child['slug']],
                        [
                            'parent_id'   => $parent->id,
                            'sort_order'  => $childOrder++,
                            'name'        => $child['name'],
                            'description' => null,
                            'status'      => true,
                        ]
                    );
                }
            }
        }
    }
}
