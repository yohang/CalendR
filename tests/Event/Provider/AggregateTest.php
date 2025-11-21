<?php

declare(strict_types=1);

namespace CalendR\Test\Event\Provider;

use CalendR\Event\EventInterface;
use CalendR\Event\Provider\Aggregate;
use CalendR\Event\Provider\ProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AggregateTest extends TestCase
{
    protected Aggregate $object;

    protected ProviderInterface&MockObject $provider1;

    protected ProviderInterface&MockObject $provider2;

    protected function setUp(): void
    {
        $this->provider1 = $this->createMock(ProviderInterface::class);
        $this->provider2 = $this->createMock(ProviderInterface::class);
        $this->object = new Aggregate([$this->provider1]);
    }

    public function testAdd(): void
    {
        $this->object->add($this->provider2);

        $reflectionClass = new \ReflectionClass($this->object);
        $providersProperty = $reflectionClass->getProperty('providers');

        $this->assertCount(2, $providersProperty->getValue($this->object));
    }

    public function testGetEvents(): void
    {
        $begin = new \DateTime();
        $end = new \DateTime();
        $event1 = $this->createMock(EventInterface::class);
        $event2 = $this->createMock(EventInterface::class);

        $this->provider1->expects($this->once())->method('getEvents')->with($begin, $end)->willReturn([$event1]);
        $this->provider2->expects($this->once())->method('getEvents')->with($begin, $end)->willReturn([$event2]);

        $this->object->add($this->provider2);

        $this->assertSame([$event1, $event2], $this->object->getEvents($begin, $end));
    }
}
