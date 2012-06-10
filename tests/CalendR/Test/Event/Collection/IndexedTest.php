<?php

namespace CalendR\Test\Event\Collection;

use CalendR\Event\Collection,
    CalendR\Event\Event,
    CalendR\Period;


class IndexedTest extends \PHPUnit_Framework_TestCase
{
    private static $events = array();
    /**
     * @var \CalendR\Event\Collection\Indexed
     */
    private $collection;

    protected function setUp()
    {
        $this->collection = new Collection\Indexed(self::$events);
    }

    public static function setUpBeforeClass()
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

    public function testAdd()
    {
        $this->collection->add(new Event(
            'event-1',
            new \DateTime('2012-05-03T10:00:00'),
            new \DateTime('2012-05-03T18:00:00')
        ));
        $this->assertSame(6, count($this->collection));

        $this->collection->add(new Event(
            'event-2',
            new \DateTime('2012-05-03T13:00:00'),
            new \DateTime('2012-05-03T16:00:00')
        ));
        $this->assertSame(7, count($this->collection));


        $this->collection->add(new Event(
            'event-2',
            new \DateTime('2012-05-05T13:00:00'),
            new \DateTime('2012-05-05T16:00:00')
        ));
        $this->assertSame(8, count($this->collection));
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

    public function testFind()
    {
        $events = $this->collection->find('2012-05-09');
        $this->assertSame(1, count($events));
        $this->assertSame('event-a', $events[0]->getUid());

        $events = $this->collection->find(new \DateTime('2012-05-09T05:56:00'));
        $this->assertSame(1, count($events));
        $this->assertSame('event-a', $events[0]->getUid());

        $events = $this->collection->find(new Period\Day(new \DateTime('2012-05-09')));
        $this->assertSame(1, count($events));
        $this->assertSame('event-a', $events[0]->getUid());
    }
}
