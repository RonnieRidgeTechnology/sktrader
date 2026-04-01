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
                'name'     => 'Sofas',
                'slug'     => 'sofas',
                'children' => [
                    ['name' => '3-Seater Sofas', 'slug' => '3-seater-sofas'],
                    ['name' => '2-Seater Sofas', 'slug' => '2-seater-sofas'],
                    ['name' => 'L-Shaped Sofas', 'slug' => 'l-shaped-sofas'],
                    ['name' => 'Corner Sofas', 'slug' => 'corner-sofas'],
                    ['name' => 'Fabric Sofas', 'slug' => 'fabric-sofas'],
                    ['name' => 'Leather Sofas', 'slug' => 'leather-sofas'],
                ],
            ],
            [
                'name'     => 'Living Room',
                'slug'     => 'living-room',
                'children' => [
                    ['name' => 'Armchairs', 'slug' => 'armchairs'],
                    ['name' => 'Coffee Tables', 'slug' => 'coffee-tables'],
                    ['name' => 'TV Units', 'slug' => 'tv-units'],
                    ['name' => 'Bookshelves', 'slug' => 'bookshelves'],
                ],
            ],
            [
                'name'     => 'Bedroom',
                'slug'     => 'bedroom',
                'children' => [
                    ['name' => 'Beds', 'slug' => 'beds'],
                    ['name' => 'Wardrobes', 'slug' => 'wardrobes'],
                    ['name' => 'Bedside Tables', 'slug' => 'bedside-tables'],
                    ['name' => 'Dressing Tables', 'slug' => 'dressing-tables'],
                ],
            ],
            [
                'name'     => 'Dining',
                'slug'     => 'dining',
                'children' => [
                    ['name' => 'Dining Tables', 'slug' => 'dining-tables'],
                    ['name' => 'Dining Chairs', 'slug' => 'dining-chairs'],
                    ['name' => 'Dining Sets', 'slug' => 'dining-sets'],
                ],
            ],
            ['name' => 'Office Furniture', 'slug' => 'office-furniture'],
            ['name' => 'Outdoor Furniture', 'slug' => 'outdoor-furniture'],
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
