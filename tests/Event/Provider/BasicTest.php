<?php

declare(strict_types=1);

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Event;
use CalendR\Event\Provider\Basic;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BasicTest extends TestCase
{
    protected Basic $object;

    protected static array $events;

    protected function setUp(): void
    {
        $this->object = new Basic();
    }

    public static function getSomeEvents(): array
    {
        return self::$events ?? self::$events = [
            new Event(new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-01T21:30')),
            new Event(new \DateTime('2011-01-01T20:30'), new \DateTime('2012-01-01T01:30')),
            new Event(new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-02T21:30')),
            new Event(new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-02T00:00')),
            new Event(new \DateTime('2015-12-28T00:00'), new \DateTime('2015-12-29T00:00')),
            new Event(new \DateTime('2025-11-01T00:00'), new \DateTime('2025-12-01T00:00')),
        ];
    }

    public function testAddAndCount(): void
    {
        $i = 0;
        $events = $this->getSomeEvents();

        $this->assertCount(0, $this->object);
        foreach ($events as $event) {
            $this->object->add($event);
            $this->assertCount(++$i, $this->object);
        }

        $this->assertSame($events, $this->object->all());
    }

    #[DataProvider('getEventsProvider')]
    public function testGetEvents(\DateTime $begin, \DateTime $end, array $expectedEvents): void
    {
        $someEvents = self::getSomeEvents();
        foreach ($someEvents as $event) {
            $this->object->add($event);
        }

        $events = $this->object->getEvents($begin, $end);
        $this->assertCount(\count($expectedEvents), $events);
        foreach ($events as $index => $event) {
            $this->assertSame($event, $someEvents[$expectedEvents[$index]]);
        }
    }

    public function testNoErrorWhenNoEvents(): void
    {
        $this->assertSame([], $this->object->getEvents(new \DateTime('2013-06-01'), new \DateTime('2013-07-01')));
    }

    public function testGetIterator(): void
    {
        $this->assertInstanceOf('Iterator', $this->object->getIterator());
    }

    public static function getEventsProvider(): \Iterator
    {
        yield [new \DateTime('2012-01-01T03:00'), new \DateTime('2012-01-01T23:59'), [0, 2, 3]];
        yield [new \DateTime('2011-11-01T20:30'), new \DateTime('2012-01-01T01:30'), [1]];
        yield [new \DateTime('2015-12-28T00:00'), new \DateTime('2015-12-28T12:00'), [4]];
        yield [new \DateTime('2015-12-27T00:00'), new \DateTime('2015-12-28T00:00'), []];
        yield [new \DateTime('2025-12-01T00:00'), new \DateTime('2026-01-01T00:00'), []];
    }
}
