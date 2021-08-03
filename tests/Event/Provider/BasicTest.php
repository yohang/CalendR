<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Event;
use CalendR\Event\Provider\Basic;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    /**
     * @var Basic
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new Basic();
    }

    public function getSomeEvents()
    {
        return array(
            new Event('event-1', new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-01T21:30')),
            new Event('event-2', new \DateTime('2011-01-01T20:30'), new \DateTime('2012-01-01T01:30')),
            new Event('event-3', new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-02T21:30')),
            new Event('event-4', new \DateTime('2012-01-01T20:30'), new \DateTime('2012-01-02T00:00')),
            new Event('event-5', new \DateTime('2015-12-28T00:00'), new \DateTime('2015-12-29T00:00')),
        );
    }

    public function testAddAndCount()
    {
        $i      = 0;
        $events = $this->getSomeEvents();

        $this->assertSame(0, count($this->object));
        foreach ($events as $event) {
            $this->object->add($event);
            $this->assertSame(++$i, count($this->object));
        }

        $this->assertSame($events, $this->object->all());
    }

    public function getEventsProvider()
    {
        return array(
            array(new \DateTime('2012-01-01T03:00'), new \DateTime('2012-01-01T23:59'), array(1, 3, 4)),
            array(new \DateTime('2011-11-01T20:30'), new \DateTime('2012-01-01T01:30'), array(2)),
            array(new \DateTime('2015-12-28T00:00'), new \DateTime('2015-12-28T12:00'), array(5)),
            array(new \DateTime('2015-12-27T00:00'), new \DateTime('2015-12-28T00:00'), array()),
        );
    }

    /**
     * @dataProvider getEventsProvider
     */
    public function testGetEvents($begin, $end, array $expectedEvents)
    {
        foreach ($this->getSomeEvents() as $event) {
            $this->object->add($event);
        }

        $events = $this->object->getEvents($begin, $end);
        $this->assertCount(count($expectedEvents), $events);
        foreach ($events as $i => $event) {
            $this->assertSame('event-'.$expectedEvents[$i], $events[$i]->getUid());
        }
    }

    public function testNoErrorWhenNoEvents()
    {
        $this->assertSame(array(), $this->object->getEvents(new \DateTime('2013-06-01'), new \DateTime('2013-07-01')));
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf('Iterator', $this->object->getIterator());
    }
}
