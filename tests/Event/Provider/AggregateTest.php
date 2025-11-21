<?php

namespace CalendR\Test\Event\Provider;

use PHPUnit\Framework\MockObject\MockObject;
use CalendR\Event\Provider\ProviderInterface;
use CalendR\Event\EventInterface;
use CalendR\Event\Provider\Aggregate;
use PHPUnit\Framework\TestCase;

class AggregateTest extends TestCase
{
    /**
     * @var Aggregate
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected MockObject $provider1;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected MockObject $provider2;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->provider1 = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $this->provider2 = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $this->object    = new Aggregate([$this->provider1]);
    }

    public function testAdd(): void
    {
        $this->object->add($this->provider2);

        $reflectionClass   = new \ReflectionClass($this->object);
        $providersProperty = $reflectionClass->getProperty('providers');

        $this->assertCount(2, $providersProperty->getValue($this->object));
    }

    public function testGetEvents(): void
    {
        $begin  = new \DateTime;
        $end    = new \DateTime;
        $event1 = $this->getMockBuilder(EventInterface::class)->getMock();
        $event2 = $this->getMockBuilder(EventInterface::class)->getMock();

        $this->provider1->expects($this->once())->method('getEvents')->with($begin, $end)->will($this->returnValue([$event1]));
        $this->provider2->expects($this->once())->method('getEvents')->with($begin, $end)->will($this->returnValue([$event2]));

        $this->object->add($this->provider2);

        $this->assertSame([$event1, $event2], $this->object->getEvents($begin, $end));
    }
}
