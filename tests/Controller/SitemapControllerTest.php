<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SitemapControllerTest extends WebTestCase
{
    public function testSitemap()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');
        $this->assertResponseHeaderSame('Content-Type', 'text/xml; charset=UTF-8');
    }
}
