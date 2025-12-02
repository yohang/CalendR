<?php

declare(strict_types=1);

namespace Bridge\Symfony\Functional;

use CalendR\Test\Bridge\Symfony\Functional\BaseTestCase;

final class TwigRenderingTest extends BaseTestCase
{
    public function testItRendersGivenMonth(): void
    {
        $crawler = $this->getClient()->request('GET', '/calendar/2026/1');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'January 2026');
        $this->assertCount(5, $crawler->filter('tbody tr'));
        $this->assertCount(6, $crawler->filter('tr'));
        $this->assertCount(31, $crawler->filter('td.inside'));
        $this->assertCount(4, $crawler->filter('td.outside'));
    }

    public function testItDisplaysPreviousMonth(): void
    {
        $crawler = $this->getClient()->request('GET', '/calendar/2026/1');
        $this->assertResponseIsSuccessful();

        $this->getClient()->click($crawler->filter('.go-to-previous')->link());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'December 2025');
    }

    public function testItDisplaysNext(): void
    {
        $crawler = $this->getClient()->request('GET', '/calendar/2026/1');
        $this->assertResponseIsSuccessful();

        $this->getClient()->click($crawler->filter('.go-to-next')->link());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'February 2026');
    }

    public function testItDisplaysEvents(): void
    {
        $crawler = $this->getClient()->request('GET', '/calendar/2025/11');
        $this->assertResponseIsSuccessful();

        $this->assertCount(40, $crawler->filter('td .event'));
    }
}
