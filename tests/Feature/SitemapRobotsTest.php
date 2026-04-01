<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapRobotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_200_and_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('</urlset>', $response->getContent());
    }

    public function test_robots_returns_200_and_references_sitemap(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertStringContainsString('User-agent:', $response->getContent());
        $this->assertStringContainsString('Sitemap:', $response->getContent());
    }
}
