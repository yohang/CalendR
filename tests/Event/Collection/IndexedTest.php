<?php

declare(strict_types=1);

namespace CalendR\Test\Event\Collection;

use CalendR\Event\Collection\IndexedCollection;
use CalendR\Event\Event;
use CalendR\Period\Day;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class IndexedTest extends TestCase
{
    use ProphecyTrait;

    private static array $events = [];
    private IndexedCollection $collection;

    protected function setUp(): void
    {
        $this->collection = new IndexedCollection(self::$events);
    }

    public static function setUpBeforeClass(): void
    {
        self::$events = [
            new Event(new \DateTime('2012-05-09T10:00:00'), new \DateTime('2012-05-09T17:00:00')),
            new Event(new \DateTime('2012-05-10T10:00:00'), new \DateTime('2012-05-10T17:00:00')),
            new Event(new \DateTime('2012-05-11T10:00:00'), new \DateTime('2012-05-11T17:00:00')),
            new Event(new \DateTime('2012-05-12T10:00:00'), new \DateTime('2012-05-12T17:00:00')),
            new Event(new \DateTime('2012-05-13T10:00:00'), new \DateTime('2012-05-13T17:00:00')),
        ];
    }

    public function testConstruct(): void
    {
        $this->assertCount(5, $this->collection);
    }

    public function getAddData(): array
    {
        return [
            [new Event(new \DateTime('2012-05-03T10:00:00'), new \DateTime('2012-05-03T18:00:00')), 6],
            [new Event(new \DateTime('2012-05-03T13:00:00'), new \DateTime('2012-05-03T16:00:00')), 7],
            [new Event(new \DateTime('2012-05-05T13:00:00'), new \DateTime('2012-05-05T16:00:00')), 8],
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
        yield ['2012-05-09', 1, 0];
        yield [new \DateTime('2012-05-09T05:56:00'), 1, 0];
        yield [new Day(new \DateTime('2012-05-09')), 1, 0];
        yield [new Day(new \DateTime('2011-05-09')), 0, null];
    }

    #[DataProvider('findProvider')]
    public function testFind(string|\DateTime|Day $index, int $count, ?int $eventIndex): void
    {
        $events = $this->collection->find($index);
        $this->assertCount($count, $events);
        if ($count > 0) {
            $this->assertSame(self::$events[$eventIndex], $events[0]);
        }
    }

    #[DataProvider('findProvider')]
    public function testHas(string|\DateTime|Day $index, int $count): void
    {
        $this->assertSame($count > 0, $this->collection->has($index));
    }

    public function testAll(): void
    {
        $this->assertCount(\count($this->collection), $this->collection->all());

        foreach ($this->collection->all() as $index => $event) {
            $this->assertSame(self::$events[$index], $event);
        }
    }
}
