<?php

namespace CalendR\Test\Event\Provider;

use CalendR\Event\Event;
use CalendR\Event\Provider\Cache;

/**
 *
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cache
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cache;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $provider;

    protected function setUp()
    {
        $this->cache    = $this->getMock('Doctrine\Common\Cache\Cache');
        $this->provider = $this->getMock('CalendR\Event\Provider\ProviderInterface');
        $this->object = new Cache($this->cache, $this->provider, 3600);
    }

    public function testItCallsProviderWhenNoCache()
    {
        $events = array(new Event('foo', new \DateTime, new \DateTime));
        $begin  = new \DateTime;
        $end    = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('contains')
            ->with(md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue(false));

        $this->cache
            ->expects($this->once())
            ->method('save')
            ->with(md5(serialize(array($begin, $end, array()))), $events, 3600)
            ->will($this->returnValue(true));

        $this->provider
            ->expects($this->once())
            ->method('getEvents')
            ->with($begin, $end, array())
            ->will($this->returnValue($events));

        $this->assertSame($events, $this->object->getEvents($begin, $end));
    }

    public function testItCallsProviderWhenCache()
    {
        $events = array(new Event('foo', new \DateTime, new \DateTime));
        $begin  = new \DateTime;
        $end    = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('contains')
            ->with(md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue(true));

        $this->cache
            ->expects($this->once())
            ->method('fetch')
            ->with(md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue($events));

        $this->provider->expects($this->never())->method('getEvents');

        $this->assertSame($events, $this->object->getEvents($begin, $end));
    }

    public function testItUseNamespaceWhenCache()
    {
        $this->object = new Cache($this->cache, $this->provider, 3600, 'ns');

        $begin  = new \DateTime;
        $end    = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->cache
            ->expects($this->once())
            ->method('contains')
            ->with('ns.'.md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue(true));

        $this->cache
            ->expects($this->once())
            ->method('fetch')
            ->with('ns.'.md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue(array()));

        $this->object->getEvents($begin, $end);
    }

    public function testItUseNamespaceWhenNoCache()
    {
        $this->object = new Cache($this->cache, $this->provider, 3600, 'ns');

        $begin  = new \DateTime;
        $end    = clone $begin;
        $end->add(new \DateInterval('P1M'));

        $this->provider
            ->expects($this->once())
            ->method('getEvents')
            ->with($begin, $end, array())
            ->will($this->returnValue(array()));

        $this->cache
            ->expects($this->once())
            ->method('contains')
            ->with('ns.'.md5(serialize(array($begin, $end, array()))))
            ->will($this->returnValue(false));

        $this->cache
            ->expects($this->once())
            ->method('save')
            ->with('ns.'.md5(serialize(array($begin, $end, array()))), array(), 3600)
            ->will($this->returnValue(true));

        $this->object->getEvents($begin, $end);
    }
}
