<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'home' => [
                'meta_title' => 'Watches, Perfumes & Skin Care Serums | SK Traders',
                'meta_description' => 'Shop curated watches, perfumes, and skin care serums at SK Traders. Premium picks, careful packing, and concierge-style support.',
                'meta_keywords' => 'watches, perfumes, skin care serums, skincare, fragrance, SK Traders',
            ],
            'about' => [
                'meta_title' => 'About Us | SK Traders',
                'meta_description' => 'SK Traders curates premium watches, perfumes, and skin care serums — luxury essentials with a modern, premium experience.',
                'meta_keywords' => 'about SK Traders, watches, perfumes, skin care serums',
            ],
            'services' => [
                'meta_title' => 'Collections | SK Traders',
                'meta_description' => 'Explore SK Traders collections: watches, perfumes, and skin care serums — curated luxury essentials with premium presentation.',
                'meta_keywords' => 'watches, perfumes, skin care serums, collections, SK Traders',
            ],
            'products' => [
                'meta_title' => 'All Products | SK Traders',
                'meta_description' => 'Shop watches, perfumes, and skin care serums at SK Traders. Curated picks with premium presentation.',
                'meta_keywords' => 'shop watches, buy perfumes, skin care serums, SK Traders',
            ],
            'contact' => [
                'meta_title' => 'Contact Us | SK Traders',
                'meta_description' => 'Contact SK Traders for orders and enquiries about watches, perfumes, and skin care serums. We respond as fast as possible.',
                'meta_keywords' => 'contact SK Traders, watches, perfumes, skincare, enquiry',
            ],
            'faq' => [
                'meta_title' => 'FAQ | SK Traders',
                'meta_description' => 'Frequently asked questions about ordering watches, perfumes, and serums — delivery, payment, and support.',
                'meta_keywords' => 'FAQ, watches, perfumes, skincare, delivery, payment, SK Traders',
            ],
            'why-choose-us' => [
                'meta_title' => 'Why Choose Us | SK Traders Zambia',
                'meta_description' => 'Curated watches, perfumes, and serums — premium presentation, careful packing, and concierge support.',
                'meta_keywords' => 'why choose us, curated watches, perfumes, serums, concierge support',
            ],
            'how-it-works' => [
                'meta_title' => 'How It Works | SK Traders',
                'meta_description' => 'From discovery to delivery: browse, checkout, and receive curated watches, perfumes, and serums with care.',
                'meta_keywords' => 'how to buy, order process, delivery process, watches, perfumes, serums',
            ],
            'quality' => [
                'meta_title' => 'Our Quality | SK Traders',
                'meta_description' => 'Quality-first selection and careful handling — curated watches, perfumes, and serums with premium presentation.',
                'meta_keywords' => 'quality, curated, watches, perfumes, serums, SK Traders',
            ],
            'private-label' => [
                'meta_title' => 'Premium Orders & Gifting | SK Traders',
                'meta_description' => 'Premium orders, gifting, and curated bundles — watches, perfumes, and skin care serums with a luxury experience.',
                'meta_keywords' => 'premium orders, gifting, bundles, watches, perfumes, serums',
            ],
            'privacy-policy' => [
                'meta_title' => 'Privacy Policy | SK Traders',
                'meta_description' => 'Privacy policy for SK Traders. How we collect, use and protect your personal information.',
                'meta_keywords' => 'privacy policy, data protection',
            ],
            'terms-and-conditions' => [
                'meta_title' => 'Terms & Conditions | SK Traders',
                'meta_description' => 'Terms and conditions for purchasing products from SK Traders.',
                'meta_keywords' => 'terms and conditions, purchase terms',
            ],
            'manufacturing-policy' => [
                'meta_title' => 'Delivery & Returns | SK Traders',
                'meta_description' => 'Delivery and returns policy for SK Traders. Nationwide shipping across Zambia.',
                'meta_keywords' => 'delivery policy, returns, shipping Zambia',
            ],
        ];

        foreach ($pages as $key => $data) {
            SeoSetting::updateOrCreate(
                ['page_key' => $key],
                array_merge($data, [
                    'canonical_url' => null,
                    'og_image' => null,
                    'og_type' => 'website',
                    'noindex' => false,
                ])
            );
        }
    }
}
