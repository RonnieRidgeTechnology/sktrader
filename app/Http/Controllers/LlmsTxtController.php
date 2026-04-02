<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Serves llms.txt – a plain-text resource for LLM/AI crawlers describing the site.
 * See https://llmstxt.org/ for the convention.
 */
class LlmsTxtController extends Controller
{
    public function index(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $name = config('zuaaz.name', 'SK Traders');
        $tagline = config('zuaaz.tagline', 'Curated Watches · Perfumes · Skin Care Serums');
        $email = config('zuaaz.contact.email', 'info@sktraders.com');
        $website = config('zuaaz.contact.website', 'www.sktraders.com');

        $body = "# {$name}\n\n"
            . "{$tagline}\n\n"
            . "## Overview\n\n"
            . "SK Traders curates premium watches, perfumes, and skin care serums. Shop luxury essentials with a modern storefront, careful packing, and concierge-style support.\n\n"
            . "## Main pages\n\n"
            . "- Home: {$base}/\n"
            . "- About: {$base}/about\n"
            . "- Collections: {$base}/services\n"
            . "- Products: {$base}/products\n"
            . "- Premium Orders & Gifting: {$base}/private-label\n"
            . "- How It Works: {$base}/how-it-works\n"
            . "- Why Choose Us: {$base}/why-choose-us\n"
            . "- Our Quality: {$base}/quality\n"
            . "- FAQ: {$base}/faq\n"
            . "- Contact: {$base}/contact\n"
            . "- Privacy Policy: {$base}/privacy-policy\n"
            . "- Terms & Conditions: {$base}/terms-and-conditions\n"
            . "- Delivery & Returns: {$base}/manufacturing-policy\n\n"
            . "## Contact\n\n"
            . "- Email: {$email}\n"
            . "- Website: {$website}\n"
            . "- WhatsApp: See contact page for current numbers.\n\n"
            . "## Sitemap\n\n"
            . "{$base}/sitemap.xml\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
