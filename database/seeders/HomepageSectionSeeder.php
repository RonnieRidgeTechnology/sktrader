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
                    'eyebrow' => 'In focus',
                    'heading' => 'Featured pick',
                    'body'    => 'A featured product can appear here when enabled. Mark products as featured in Admin → Products.',
                ],
            ],
            [
                'key'        => 'journey_strip',
                'title'      => 'Journey strip',
                'sort_order' => 30,
                'data'       => [
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
            [
                'key'        => 'why_pillars',
                'title'      => 'Why us',
                'sort_order' => 40,
                'data'       => [
                    'heading' => 'Curated luxury essentials, delivered with care.',
                    'pillars' => [
                        ['title' => 'Curated selection', 'text' => 'Focused picks across watches, perfumes, and serums — chosen for quality and premium presentation.'],
                        ['title' => 'Concierge support', 'text' => 'Quick help via WhatsApp — sizes, scent profiles, routine tips, and order updates.'],
                        ['title' => 'Careful delivery', 'text' => 'Careful packing and a reliable delivery flow so your order arrives protected.'],
                    ],
                ],
            ],
            [
                'key'        => 'reels_strip',
                'title'      => 'Video reels',
                'sort_order' => 50,
                'data'       => [
                    'eyebrow' => 'Reels',
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
                    'eyebrow'  => 'Delivery',
                    'headline' => 'Carefully packed. Delivered with confidence.',
                    'regions'  => ['Lusaka', 'Copperbelt', 'Southern', 'Central', 'Northern', 'Nationwide'],
                    'footer'   => 'Contact us for delivery details and lead times.',
                ],
            ],
            [
                'key'        => 'final_cta',
                'title'      => 'Final CTA',
                'sort_order' => 80,
                'data'       => [
                    'headline'            => 'Ready to find your next signature?',
                    'primary_button'      => 'Chat on WhatsApp',
                    'secondary_button'    => 'Contact us',
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
                        'headline'       => 'Luxury essentials, curated.',
                        'subheading'     => 'Watches, perfumes, and skin care serums — premium picks, delivered with care.',
                        'image_left'     => null,
                        'image_right'    => null,
                        'gallery_images' => [],
                    ], $section->data ?? []),
                ]);
            }
        }
    }
}
