<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Sections match the public home page order and content.
     */
    public function run(): void
    {
        $sections = [
            [
                'key'        => 'hero',
                'title'      => 'Room hero & Bento',
                'sort_order' => 10,
                'data'       => null, // preserve existing hero data; only set title/sort_order
            ],
            [
                'key'        => 'spotlight',
                'title'      => 'Spotlight',
                'sort_order' => 20,
                'data'       => [
                    'eyebrow' => 'In the spotlight',
                    'heading' => 'Featured piece',
                    'body'    => 'Our first featured product appears here. Mark a product as featured in Products to show it.',
                ],
            ],
            [
                'key'        => 'journey_strip',
                'title'      => 'Journey strip',
                'sort_order' => 30,
                'data'       => [
                    'eyebrow' => 'From idea to home',
                    'steps'   => [
                        ['title' => 'Browse or visit', 'line' => 'Online or at our Lusaka showroom.'],
                        ['title' => 'Choose your piece', 'line' => 'Sofas and furniture for your space.'],
                        ['title' => 'Confirm order', 'line' => 'We arrange payment and delivery.'],
                        ['title' => 'We prepare', 'line' => 'Quality checks and careful packing.'],
                        ['title' => 'Delivery', 'line' => 'To your door across Zambia.'],
                    ],
                ],
            ],
            [
                'key'        => 'why_pillars',
                'title'      => 'Why us',
                'sort_order' => 40,
                'data'       => [
                    'heading' => 'Quality sofas, showroom in Lusaka, nationwide delivery.',
                    'pillars' => [
                        ['title' => 'Quality first', 'text' => 'Durable materials and solid construction. Sofas and furniture built to last in your home.'],
                        ['title' => 'Showroom in Lusaka', 'text' => 'Try before you buy. Visit us to see and feel the pieces in person.'],
                        ['title' => 'Nationwide delivery', 'text' => 'We deliver across Zambia. From Lusaka to your door, we get it there safely.'],
                    ],
                ],
            ],
            [
                'key'        => 'reels_strip',
                'title'      => 'Video reels',
                'sort_order' => 50,
                'data'       => [
                    'eyebrow' => 'Showroom & style',
                    'heading' => 'Videos are managed in Admin → Video Reels.',
                ],
            ],
            [
                'key'        => 'quote_block',
                'title'      => 'Testimonials',
                'sort_order' => 60,
                'data'       => [
                    'eyebrow' => 'What people say',
                    'heading' => 'Testimonials are managed in Admin → Testimonials.',
                ],
            ],
            [
                'key'        => 'delivery_line',
                'title'      => 'Delivery line',
                'sort_order' => 70,
                'data'       => [
                    'eyebrow'  => 'We deliver across Zambia',
                    'headline' => 'From our showroom in Lusaka to your home — nationwide.',
                    'regions'  => ['Lusaka', 'Copperbelt', 'Southern', 'Central', 'Northern', 'Nationwide'],
                    'footer'   => 'Contact us for delivery details and lead times.',
                ],
            ],
            [
                'key'        => 'final_cta',
                'title'      => 'Final CTA',
                'sort_order' => 80,
                'data'       => [
                    'headline'            => 'Ready to find your piece?',
                    'primary_button'      => 'Chat on WhatsApp',
                    'secondary_button'    => 'Visit showroom',
                ],
            ],
        ];

        foreach ($sections as $s) {
            $payload = [
                'title'      => $s['title'],
                'visible'    => true,
                'sort_order' => $s['sort_order'],
            ];
            if (array_key_exists('data', $s) && $s['data'] !== null) {
                $payload['data'] = $s['data'];
            }
            $section = HomepageSection::updateOrCreate(
                ['key' => $s['key']],
                $payload
            );
            // Hero: ensure default headline/subheading exist without wiping gallery
            if ($s['key'] === 'hero' && empty($section->data['headline'])) {
                $section->update([
                    'data' => array_merge([
                        'headline'       => 'Quality Sofas & Furniture for Every Home',
                        'subheading'     => 'Comfort, Style & Value in Zambia',
                        'image_left'     => null,
                        'image_right'    => null,
                        'gallery_images' => [],
                    ], $section->data ?? []),
                ]);
            }
        }
    }
}
