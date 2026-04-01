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
                'meta_title' => 'Sofas & Furniture Store Zambia | SK Traders Lusaka',
                'meta_description' => 'Quality sofas and furniture in Zambia. SK Traders offers living room sofas, armchairs and home furniture. Visit us in Lusaka or order nationwide.',
                'meta_keywords' => 'sofas Zambia, furniture Lusaka, garden furniture, living room furniture, SK Traders',
            ],
            'about' => [
                'meta_title' => 'About Us | SK Traders Zambia',
                'meta_description' => 'SK Traders – your trusted furniture store in Zambia. Quality sofas and living room furniture in Lusaka and nationwide delivery.',
                'meta_keywords' => 'about SK Traders, furniture store Zambia, Lusaka showroom',
            ],
            'services' => [
                'meta_title' => 'Our Collections | SK Traders',
                'meta_description' => 'Browse our furniture collections: sofas, living room, bedroom and office furniture. Quality pieces for every home in Zambia.',
                'meta_keywords' => 'furniture collections, sofas, living room, bedroom furniture Zambia',
            ],
            'products' => [
                'meta_title' => 'Furniture & Sofas | SK Traders Zambia',
                'meta_description' => 'Shop sofas, armchairs, rattan and garden furniture. SK Traders – quality furniture in Lusaka and across Zambia.',
                'meta_keywords' => 'buy sofas Zambia, furniture shop, garden furniture, rattan furniture Lusaka',
            ],
            'contact' => [
                'meta_title' => 'Contact Us | SK Traders',
                'meta_description' => 'Contact SK Traders for sofas and furniture in Zambia. Visit our Lusaka showroom or send an enquiry.',
                'meta_keywords' => 'contact SK Traders, Lusaka showroom, furniture enquiry Zambia',
            ],
            'faq' => [
                'meta_title' => 'FAQ | SK Traders',
                'meta_description' => 'Frequently asked questions about our furniture, delivery in Zambia, payment, warranty and nationwide shipping.',
                'meta_keywords' => 'FAQ, furniture delivery Zambia, payment, warranty, SK Traders',
            ],
            'why-choose-us' => [
                'meta_title' => 'Why Choose Us | SK Traders Zambia',
                'meta_description' => 'Quality sofas and furniture, showroom in Lusaka, nationwide delivery, warranty and friendly service. SK Traders Zambia.',
                'meta_keywords' => 'why choose us, quality furniture Zambia, Lusaka showroom, nationwide delivery',
            ],
            'how-it-works' => [
                'meta_title' => 'How It Works | SK Traders',
                'meta_description' => 'From browsing to delivery: visit our showroom or order online. We deliver sofas and furniture across Zambia.',
                'meta_keywords' => 'how to buy furniture Zambia, order process, delivery process',
            ],
            'quality' => [
                'meta_title' => 'Our Quality | SK Traders',
                'meta_description' => 'We choose durable materials and solid construction. Quality sofas and furniture for your home in Zambia.',
                'meta_keywords' => 'quality furniture, durable sofas, materials, construction Zambia',
            ],
            'private-label' => [
                'meta_title' => 'Custom Furniture | SK Traders',
                'meta_description' => 'Custom and made-to-order furniture in Zambia. SK Traders can help with bespoke sofas and pieces for your space.',
                'meta_keywords' => 'custom furniture, made to order, bespoke sofas Zambia',
            ],
            'privacy-policy' => [
                'meta_title' => 'Privacy Policy | SK Traders',
                'meta_description' => 'Privacy policy for SK Traders. How we collect, use and protect your personal information.',
                'meta_keywords' => 'privacy policy, data protection',
            ],
            'terms-and-conditions' => [
                'meta_title' => 'Terms & Conditions | SK Traders',
                'meta_description' => 'Terms and conditions for purchasing furniture from SK Traders Zambia.',
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
