<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $sitemapUrl = rtrim(config('app.url'), '/') . '/sitemap.xml';
        $base = rtrim(config('app.url'), '/');
        $body = "User-agent: *\n"
            . "Disallow: /admin\n"
            . "Disallow: /dashboard\n"
            . "Disallow: /profile\n"
            . "Disallow: /login\n"
            . "Disallow: /register\n"
            . "Disallow: /inquiry\n"
            . "Disallow: /inquiry/thank-you\n"
            . "Allow: /\n"
            . "\n"
            . "Sitemap: {$sitemapUrl}\n"
            . "\n# LLM / AI crawlers: human-readable site info\n"
            . "# " . $base . "/llms.txt\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
