<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Provider\Cache,
    CalendR\Event\Provider\ProviderInterface,
    CalendR\Event\Event;

class CacheTest extends \PHPUnit_Framework_TestCase implements ProviderInterface
{
    /**
     * @var Cache
     */
    protected $object;

    /**
     * @var int
     */
    protected $calls = 0;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Cache($this);
    }

    public function getEventsProvider()
    {
        return array(
            array(new \DateTime('2012-01-01 20:30'), new \DateTime('2012-01-01 21:30')),
            array(new \DateTime('2011-01-01 20:30'), new \DateTime('2012-01-01 21:30')),
            array(new \DateTime('2012-01-01 20:30'), new \DateTime('2012-01-02 21:30')),
            array(new \DateTime('2012-01-01 20:30'), new \DateTime('2012-01-02 00:00')),
        );
    }

    /**
     * @dataProvider getEventsProvider
     */
    public function testGetEvents($begin, $end)
    {
        $calls = $this->calls;
        $events = $this->object->getEvents($begin, $end);
        $this->assertEquals(++$calls, $this->calls);
        $events2 = $this->object->getEvents($begin, $end);
        $this->assertEquals($calls, $this->calls);

        $this->assertEquals(1, count($events));
        $this->assertEquals(0, count($events2));
    }

    /*
     * Mock methods
     */

    /**
     * Return events that matches to $begin && $end
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     */
    public function getEvents(\DateTime $begin, \DateTime $end)
    {
        $this->calls++;

        return array(new Event(uniqid(), $begin, $end));
    }
}