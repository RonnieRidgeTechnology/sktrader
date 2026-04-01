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
                'meta_title' => 'Sofas & Furniture Store Zambia | Atlantic Garden Furniture Lusaka',
                'meta_description' => 'Quality sofas and furniture in Zambia. Atlantic Garden Furniture offers living room sofas, armchairs and home furniture. Visit us in Lusaka or order nationwide.',
                'meta_keywords' => 'sofas Zambia, furniture Lusaka, garden furniture, living room furniture, Atlantic Garden Furniture',
            ],
            'about' => [
                'meta_title' => 'About Us | Atlantic Garden Furniture Zambia',
                'meta_description' => 'Atlantic Garden Furniture – your trusted furniture store in Zambia. Quality sofas and living room furniture in Lusaka and nationwide delivery.',
                'meta_keywords' => 'about Atlantic Garden Furniture, furniture store Zambia, Lusaka showroom',
            ],
            'services' => [
                'meta_title' => 'Our Collections | Atlantic Garden Furniture',
                'meta_description' => 'Browse our furniture collections: sofas, living room, bedroom and office furniture. Quality pieces for every home in Zambia.',
                'meta_keywords' => 'furniture collections, sofas, living room, bedroom furniture Zambia',
            ],
            'products' => [
                'meta_title' => 'Furniture & Sofas | Atlantic Garden Furniture Zambia',
                'meta_description' => 'Shop sofas, armchairs, rattan and garden furniture. Atlantic Garden Furniture – quality furniture in Lusaka and across Zambia.',
                'meta_keywords' => 'buy sofas Zambia, furniture shop, garden furniture, rattan furniture Lusaka',
            ],
            'contact' => [
                'meta_title' => 'Contact Us | Atlantic Garden Furniture',
                'meta_description' => 'Contact Atlantic Garden Furniture for sofas and furniture in Zambia. Visit our Lusaka showroom or send an enquiry.',
                'meta_keywords' => 'contact Atlantic Garden Furniture, Lusaka showroom, furniture enquiry Zambia',
            ],
            'faq' => [
                'meta_title' => 'FAQ | Atlantic Garden Furniture',
                'meta_description' => 'Frequently asked questions about our furniture, delivery in Zambia, payment, warranty and nationwide shipping.',
                'meta_keywords' => 'FAQ, furniture delivery Zambia, payment, warranty, Atlantic Garden',
            ],
            'why-choose-us' => [
                'meta_title' => 'Why Choose Us | Atlantic Garden Furniture Zambia',
                'meta_description' => 'Quality sofas and furniture, showroom in Lusaka, nationwide delivery, warranty and friendly service. Atlantic Garden Furniture Zambia.',
                'meta_keywords' => 'why choose us, quality furniture Zambia, Lusaka showroom, nationwide delivery',
            ],
            'how-it-works' => [
                'meta_title' => 'How It Works | Atlantic Garden Furniture',
                'meta_description' => 'From browsing to delivery: visit our showroom or order online. We deliver sofas and furniture across Zambia.',
                'meta_keywords' => 'how to buy furniture Zambia, order process, delivery process',
            ],
            'quality' => [
                'meta_title' => 'Our Quality | Atlantic Garden Furniture',
                'meta_description' => 'We choose durable materials and solid construction. Quality sofas and furniture for your home in Zambia.',
                'meta_keywords' => 'quality furniture, durable sofas, materials, construction Zambia',
            ],
            'private-label' => [
                'meta_title' => 'Custom Furniture | Atlantic Garden Furniture',
                'meta_description' => 'Custom and made-to-order furniture in Zambia. Atlantic Garden Furniture can help with bespoke sofas and pieces for your space.',
                'meta_keywords' => 'custom furniture, made to order, bespoke sofas Zambia',
            ],
            'privacy-policy' => [
                'meta_title' => 'Privacy Policy | Atlantic Garden Furniture',
                'meta_description' => 'Privacy policy for Atlantic Garden Furniture. How we collect, use and protect your personal information.',
                'meta_keywords' => 'privacy policy, data protection',
            ],
            'terms-and-conditions' => [
                'meta_title' => 'Terms & Conditions | Atlantic Garden Furniture',
                'meta_description' => 'Terms and conditions for purchasing furniture from Atlantic Garden Furniture Zambia.',
                'meta_keywords' => 'terms and conditions, purchase terms',
            ],
            'manufacturing-policy' => [
                'meta_title' => 'Delivery & Returns | Atlantic Garden Furniture',
                'meta_description' => 'Delivery and returns policy for Atlantic Garden Furniture. Nationwide shipping across Zambia.',
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
