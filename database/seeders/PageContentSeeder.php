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
                'hero_subtitle' => 'SK Traders curates premium watches, perfumes, and skin care serums — luxury essentials with a modern, premium experience.',
                'intro' => 'We curate **watches**, **perfumes**, and **skin care serums** with a quality-first lens. Our goal is simple: make it easy to discover premium picks, choose with confidence, and enjoy a smooth buying experience.',
                'owner_name' => 'Mary Daka',
                'owner_image' => 'page-content/about/owner.png',
                'facilities_heading' => 'What we offer',
                'facilities_list' => "Watches: Refined timepieces for daily and occasion wear\nPerfumes: Signature scents from fresh to deep profiles\nSerums: Routine-friendly skin care essentials\nSupport: Concierge help via WhatsApp\nDelivery: Careful packing and reliable dispatch",
            ],
            'contact' => [
                'hero_eyebrow' => 'Get in touch',
                'hero_title' => 'Contact Us',
                'hero_subtitle' => 'Send a message or chat on WhatsApp. We respond as fast as possible.',
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
                $content = ['hero_title' => 'Premium orders & gifting', 'hero_subtitle' => '', 'body' => ''];
            }
            PageContent::updateOrCreate(
                ['page_key' => $pageKey],
                ['name' => $pageConfig['name'], 'content' => $content]
            );
        }
    }
}
