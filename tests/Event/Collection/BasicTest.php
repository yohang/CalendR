<?php

declare(strict_types=1);

namespace CalendR\Test\Event\Collection;

use CalendR\Event\Collection\Basic;
use CalendR\Event\Event;
use CalendR\Period\Day;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class BasicTest extends TestCase
{
    use ProphecyTrait;

    private static array $events = [];
    private Basic $collection;

    protected function setUp(): void
    {
        $this->collection = new Basic(self::$events);
    }

    public static function setUpBeforeClass(): void
    {
        self::$events = [
            new Event(new \DateTime('2012-05-09T10:00:00'), new \DateTime('2012-05-09T17:00:00'), 'event-a'),
            new Event(new \DateTime('2012-05-10T10:00:00'), new \DateTime('2012-05-10T17:00:00'), 'event-b'),
            new Event(new \DateTime('2012-05-11T10:00:00'), new \DateTime('2012-05-11T17:00:00'), 'event-c'),
            new Event(new \DateTime('2012-05-12T10:00:00'), new \DateTime('2012-05-12T17:00:00'), 'event-d'),
            new Event(new \DateTime('2012-05-13T10:00:00'), new \DateTime('2012-05-13T17:00:00'), 'event-e'),
        ];
    }

    public function testConstruct(): void
    {
        $this->assertCount(5, $this->collection);
    }

    public function getAddData(): array
    {
        return [
            [new Event(new \DateTime('2012-05-03T10:00:00'), new \DateTime('2012-05-03T18:00:00'), 'event-1'), 6],
            [new Event(new \DateTime('2012-05-03T13:00:00'), new \DateTime('2012-05-03T16:00:00'), 'event-2'), 7],
            [new Event(new \DateTime('2012-05-05T13:00:00'), new \DateTime('2012-05-05T16:00:00'), 'event-3'), 8],
        ];
    }

    public function testAdd(): void
    {
        foreach ($this->getAddData() as $data) {
            $this->collection->add($data[0]);
            $this->assertCount($data[1], $this->collection);
        }
    }

    public function testRemove(): void
    {
        // Remove an event
        $this->collection->remove(self::$events[2]);
        $this->assertCount(4, $this->collection);

        // Remove the same event, nothing should happen
        $this->collection->remove(self::$events[2]);
        $this->assertCount(4, $this->collection);

        // Remove an other event
        $this->collection->remove(self::$events[4]);
        $this->assertCount(3, $this->collection);
    }

    public static function findProvider(): \Iterator
    {
        yield [new \DateTime('2012-05-09T11:56:00'), 1, 'event-a'];
        yield [new Day(new \DateTime('2012-05-09')), 1, 'event-a'];
        yield [new Day(new \DateTime('2011-05-09')), 0, null];
    }

    #[DataProvider('findProvider')]
    public function testFind(\DateTime|Day $index, int $count, ?string $eventUid): void
    {
        $events = $this->collection->find($index);
        $this->assertCount($count, $events);
        if ($count > 0) {
            $this->assertSame($eventUid, $events[0]->getUid());
        }
    }

    #[DataProvider('findProvider')]
    public function testHas(\DateTime|Day $index, int $count): void
    {
        $this->assertSame($count > 0, $this->collection->has($index));
    }

    public function testAll(): void
    {
        $index = \ord('a');
        foreach ($this->collection->all() as $event) {
            $this->assertSame('event-'.\chr($index++), $event->getUid());
        }
        $this->assertCount(\count($this->collection), $this->collection->all());
    }
}
