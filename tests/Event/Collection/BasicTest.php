<?php

namespace CalendR\Test\Event\Collection;

use CalendR\Event\Collection;
use CalendR\Event\Event;
use CalendR\Period;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BasicTest extends TestCase
{
    use ProphecyTrait;

    private static $events = array();
    /**
     * @var \CalendR\Event\Collection\Basic
     */
    private $collection;

    protected function setUp(): void
    {
        $this->collection = new Collection\Basic(self::$events);
    }

    public static function setUpBeforeClass(): void
    {
        self::$events = array(
            new Event('event-a', new \DateTime('2012-05-09T10:00:00'), new \DateTime('2012-05-09T17:00:00')),
            new Event('event-b', new \DateTime('2012-05-10T10:00:00'), new \DateTime('2012-05-10T17:00:00')),
            new Event('event-c', new \DateTime('2012-05-11T10:00:00'), new \DateTime('2012-05-11T17:00:00')),
            new Event('event-d', new \DateTime('2012-05-12T10:00:00'), new \DateTime('2012-05-12T17:00:00')),
            new Event('event-e', new \DateTime('2012-05-13T10:00:00'), new \DateTime('2012-05-13T17:00:00')),
        );
    }

    public function testConstruct()
    {
        $this->assertSame(5, count($this->collection));
    }

    public function getAddData()
    {
        return array(
            array(new Event('event-1',new \DateTime('2012-05-03T10:00:00'),new \DateTime('2012-05-03T18:00:00')), 6),
            array(new Event('event-2',new \DateTime('2012-05-03T13:00:00'),new \DateTime('2012-05-03T16:00:00')), 7),
            array(new Event('event-3',new \DateTime('2012-05-05T13:00:00'),new \DateTime('2012-05-05T16:00:00')), 8),
        );
    }

    public function testAdd()
    {
        foreach ($this->getAddData() as $data) {
            $this->collection->add($data[0]);
            $this->assertSame($data[1], count($this->collection));
        }
    }

    public function testRemove()
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

    public function findProvider()
    {
        $factory = $this->prophesize(Period\FactoryInterface::class)->reveal();

        return array(
            array(new \DateTime('2012-05-09T11:56:00'), 1, 'event-a'),
            array(new Period\Day(new \DateTime('2012-05-09'), $factory), 1, 'event-a'),
            array(new Period\Day(new \DateTime('2011-05-09'), $factory), 0, null),
        );
    }

    /**
     * @dataProvider findProvider
     */
    public function testFind($index, $count, $eventUid)
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
    public function testHas($index, $count)
    {
        $this->assertSame($count > 0, $this->collection->has($index));
    }

    public function testAll()
    {
        $index = ord('a');
        foreach ($this->collection->all() as $event) {
            $this->assertSame('event-'.chr($index++), $event->getUid());
        }
        $this->assertSame(count($this->collection), count($this->collection->all()));
    }
}
