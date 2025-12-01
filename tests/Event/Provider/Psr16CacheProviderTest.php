<?php

declare(strict_types=1);

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Event;
use CalendR\Event\Provider\ProviderInterface;
use CalendR\Event\Provider\Psr16CacheProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

final class Psr16CacheProviderTest extends TestCase
{
    protected Psr16CacheProvider $object;

    protected CacheInterface&MockObject $cache;

    protected ProviderInterface&MockObject $provider;

    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheInterface::class);
        $this->provider = $this->createMock(ProviderInterface::class);
        $this->object = new Psr16CacheProvider($this->cache, $this->provider, 3600);
    }

    public function testItCallsProviderWhenNoCache(): void
    {
        $events = [
            new Event(new \DateTime(), new \DateTime(), 'foo'),
            new Event(new \DateTime(), new \DateTime(), 'bar'),
            new Event(new \DateTime(), new \DateTime(), 'baz'),
        ];
        $begin = new \DateTime();
        $end = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('has')
            ->with(md5(serialize([$begin, $end, []])))
            ->willReturn(false);

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with(md5(serialize([$begin, $end, []])), $events, 3600)
            ->willReturn(true);

        $this->provider
            ->expects($this->once())
            ->method('getEvents')
            ->with($begin, $end, [])
            ->willReturn($events);

        $this->assertSame($events, $this->object->getEvents($begin, $end));
    }

    public function testItCallsProviderWhenCache(): void
    {
        $events = [
            new Event(new \DateTime(), new \DateTime(), 'foo'),
            new Event(new \DateTime(), new \DateTime(), 'bar'),
            new Event(new \DateTime(), new \DateTime(), 'baz'),
        ];
        $begin = new \DateTime();
        $end = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('has')
            ->with(md5(serialize([$begin, $end, []])))
            ->willReturn(true);

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with(md5(serialize([$begin, $end, []])))
            ->willReturn($events);

        $this->provider->expects($this->never())->method('getEvents');

        $this->assertSame($events, $this->object->getEvents($begin, $end));
    }

    public function testItUseNamespaceWhenCache(): void
    {
        $this->object = new Psr16CacheProvider($this->cache, $this->provider, 3600, 'ns');

        $begin = new \DateTime();
        $end = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('has')
            ->with('ns.'.md5(serialize([$begin, $end, []])))
            ->willReturn(true);

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('ns.'.md5(serialize([$begin, $end, []])))
            ->willReturn([]);

        $this->object->getEvents($begin, $end);
    }

    public function testItUseNamespaceWhenNoCache(): void
    {
        $this->object = new Psr16CacheProvider($this->cache, $this->provider, 3600, 'ns');

        $begin = new \DateTime();
        $end = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->provider
            ->expects($this->once())
            ->method('getEvents')
            ->with($begin, $end, [])
            ->willReturn([]);

        $this->cache
            ->expects($this->once())
            ->method('has')
            ->with('ns.'.md5(serialize([$begin, $end, []])))
            ->willReturn(false);

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with('ns.'.md5(serialize([$begin, $end, []])), [], 3600)
            ->willReturn(true);

        $this->object->getEvents($begin, $end);
    }
}
