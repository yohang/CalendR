<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Provider\Aggregate,
    CalendR\Event\Provider\ProviderInterface,
    CalendR\Event\Event;

class AggregateTest extends \PHPUnit_Framework_TestCase implements ProviderInterface
{
    /**
     * @var Cache
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Aggregate(array($this, $this));
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
        $this->assertEquals(2, count($this->object->getEvents($begin, $end)));
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
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        return array(new Event(uniqid(), $begin, $end));
    }
}
