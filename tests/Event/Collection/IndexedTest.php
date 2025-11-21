<?php

namespace CalendR\Test\Event\Collection;

use CalendR\Event\Collection\Indexed;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Day;
use CalendR\Event\Collection,
    CalendR\Event\Event,
    CalendR\Period;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class IndexedTest extends TestCase
{
    use ProphecyTrait;

    private static array $events = [];
    private Indexed $collection;

    protected function setUp(): void
    {
        $this->collection = new Indexed(self::$events);
    }

    public static function setUpBeforeClass(): void
    {
        self::$events = [
            new Event('event-a', new \DateTime('2012-05-09T10:00:00'), new \DateTime('2012-05-09T17:00:00')),
            new Event('event-b', new \DateTime('2012-05-10T10:00:00'), new \DateTime('2012-05-10T17:00:00')),
            new Event('event-c', new \DateTime('2012-05-11T10:00:00'), new \DateTime('2012-05-11T17:00:00')),
            new Event('event-d', new \DateTime('2012-05-12T10:00:00'), new \DateTime('2012-05-12T17:00:00')),
            new Event('event-e', new \DateTime('2012-05-13T10:00:00'), new \DateTime('2012-05-13T17:00:00')),
        ];
    }

    public function testConstruct(): void
    {
        $this->assertSame(5, count($this->collection));
    }

    public function getAddData(): array
    {
        return [
            [new Event('event-1',new \DateTime('2012-05-03T10:00:00'),new \DateTime('2012-05-03T18:00:00')), 6],
            [new Event('event-2',new \DateTime('2012-05-03T13:00:00'),new \DateTime('2012-05-03T16:00:00')), 7],
            [new Event('event-3',new \DateTime('2012-05-05T13:00:00'),new \DateTime('2012-05-05T16:00:00')), 8],
        ];
    }

    public function testAdd(): void
    {
        foreach ($this->getAddData() as $data) {
            $this->collection->add($data[0]);
            $this->assertSame($data[1], count($this->collection));
        }
    }

    public function testRemove(): void
    {
        // Remove an event
        $this->collection->remove(self::$events[2]);
        $this->assertSame(4, count($this->collection));

        // Remove the same event, nothing should happen
        $this->collection->remove(self::$events[2]);
        $this->assertSame(4, count($this->collection));

        // Remove an other event
        $this->collection->remove(self::$events[4]);
        $this->assertSame(3, count($this->collection));
    }

    public function findProvider(): array
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return [
            ['2012-05-09', 1, 'event-a'],
            [new \DateTime('2012-05-09T05:56:00'), 1, 'event-a'],
            [new Day(new \DateTime('2012-05-09'), $factory), 1, 'event-a'],
            [new Day(new \DateTime('2011-05-09'), $factory), 0, null],
        ];
    }

    /**
     * @dataProvider findProvider
     */
    public function testFind(string|\DateTime|Day $index, int $count, ?string $eventUid): void
    {
        $events = $this->collection->find($index);
        $this->assertSame($count, count($events));
        if ($count > 0) {
            $this->assertSame($eventUid, $events[0]->getUid());
        }
    }

    /**
     * @dataProvider findProvider
     */
    public function testHas(string|\DateTime|Day $index, int $count): void
    {
        $this->assertSame($count > 0, $this->collection->has($index));
    }

    public function testAll(): void
    {
        $index = ord('a');
        foreach ($this->collection->all() as $event) {
            $this->assertSame('event-'.chr($index++), $event->getUid());
        }
        $this->assertSame(count($this->collection), count($this->collection->all()));
    }
}
