<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'about' => [
                'hero_title' => 'About Us',
                'hero_subtitle' => 'Atlantic Garden Furniture is a furniture store in Zambia, specialising in sofas and living room furniture for homes and businesses.',
                'intro' => 'Based in **Lusaka**, we bring you a curated range of **sofas**, **armchairs**, and **living room furniture** to suit every style and budget. We focus on comfort, durability, and value so you can create the home you love. Whether you are furnishing your first home or upgrading your space, we are here to help.',
                'owner_name' => 'Mary Daka',
                'owner_image' => 'page-content/about/owner.png',
                'facilities_heading' => 'What we offer',
                'facilities_list' => "Lusaka: Showroom and main store\nNationwide: Delivery across Zambia",
            ],
            'contact' => [
                'hero_eyebrow' => 'Get in touch',
                'hero_title' => 'Contact Us',
                'hero_subtitle' => 'Visit our showroom, call us, or send a message. We respond within 24 hours.',
                'quote_heading' => 'Send an enquiry',
                'quote_subtitle' => 'Tell us what you are looking for and we will get back to you with details and pricing.',
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'content' => '',
            ],
            'manufacturing-policy' => [
                'title' => 'Delivery & Returns',
                'content' => '',
            ],
            'terms-and-conditions' => [
                'title' => 'Terms & Conditions',
                'content' => '',
            ],
            'inquiry-thank-you' => [
                'title' => 'Thank You',
                'message' => 'We have received your enquiry and will get back to you within 24 hours.',
            ],
        ];

        $config = config('page_content.pages', []);
        foreach ($config as $pageKey => $pageConfig) {
            $content = $defaults[$pageKey] ?? [];
            if (in_array($pageKey, ['services', 'why-choose-us', 'how-it-works', 'quality'], true)) {
                $content = ['hero_title' => $pageConfig['name'], 'hero_subtitle' => '', 'steps' => []];
            }
            if ($pageKey === 'private-label') {
                $content = ['hero_title' => 'Custom Furniture', 'hero_subtitle' => '', 'body' => ''];
            }
            PageContent::updateOrCreate(
                ['page_key' => $pageKey],
                ['name' => $pageConfig['name'], 'content' => $content]
            );
        }
    }
}
