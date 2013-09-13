<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Provider\Aggregate;

class AggregateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Aggregate
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $provider1;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $provider2;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->provider1 = $this->getMock('CalendR\Event\Provider\ProviderInterface');
        $this->provider2 = $this->getMock('CalendR\Event\Provider\ProviderInterface');
        $this->object    = new Aggregate(array($this->provider1));
    }

    public function testAdd()
    {
        $this->object->add($this->provider2);

        $reflectionClass   = new \ReflectionClass($this->object);
        $providersProperty = $reflectionClass->getProperty('providers');
        $providersProperty->setAccessible(true);

        $this->assertCount(2, $providersProperty->getValue($this->object));
    }

    public function testGetEvents()
    {
        $begin  = new \DateTime;
        $end    = new \DateTime;
        $event1 = $this->getMock('CalendR\Event\EventInterface');
        $event2 = $this->getMock('CalendR\Event\EventInterface');

        $this->provider1->expects($this->once())->method('getEvents')->with($begin, $end)->will($this->returnValue(array($event1)));
        $this->provider2->expects($this->once())->method('getEvents')->with($begin, $end)->will($this->returnValue(array($event2)));

        $this->object->add($this->provider2);

        $this->assertSame(array($event1, $event2), $this->object->getEvents($begin, $end));
    }
}
