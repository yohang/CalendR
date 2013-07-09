<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Provider\Basic,
    CalendR\Event\Provider\ProviderInterface,
    CalendR\Event\Event;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Basic
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
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
        );
    }

    public function testAddAndCount()
    {
        $i = 0;
        $this->assertSame(0, count($this->object));
        foreach ($this->getSomeEvents() as $event) {
            $this->object->add($event);
            $this->assertSame(++$i, count($this->object));
        }
    }

    public function getEventsProvider()
    {
        return array(
            array(new \DateTime('2012-01-01T03:00'), new \DateTime('2012-01-01T23:59'), array(1, 3, 4)),
            array(new \DateTime('2011-11-01T20:30'), new \DateTime('2012-01-01T01:30'), array(2)),
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
        $this->assertSame(count($expectedEvents), count($events));
        foreach ($events as $i => $event) {
            $this->assertSame('event-'.$expectedEvents[$i], $events[$i]->getUid());
        }
    }

    public function testNoErrorWhenNoEvents()
    {
        $this->assertSame(array(), $this->object->getEvents(new \DateTime('2013-06-01'), new \DateTime('2013-07-01')));
    }
}
