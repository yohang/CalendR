<?php

namespace CalendR\Test\Extension\Twig;

use CalendR\Extension\Twig\CalendRExtension;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class CalendRExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CalendRExtension
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $calendar;

    protected function setUp()
    {
        $this->calendar = $this->getMock('CalendR\Calendar');
        $this->object   = new CalendRExtension($this->calendar);
    }

    public function testItReturnsFunctionNames()
    {

        $functions = array_map(
            function (\Twig_SimpleFunction $fn) { return $fn->getName(); },
            $this->object->getFunctions()
        );

        $this->assertContains('calendr_year', $functions);
        $this->assertContains('calendr_month', $functions);
        $this->assertContains('calendr_week', $functions);
        $this->assertContains('calendr_day', $functions);
        $this->assertContains('calendr_events', $functions);
    }

    public function testItCallsCalendarFunctions()
    {
        foreach (array('year', 'month', 'week', 'day') as $periodName) {
            $period = $this->getMock('CalendR\Perdiod\PeriodInterface');
            $this->calendar
                ->expects($this->once())
                ->method('get' . ucfirst($periodName))
                ->with('foo', 'bar')
                ->will($this->returnValue($period));

            $this->assertSame($period, $this->object->{'get' . ucfirst($periodName)}('foo', 'bar'));
        }

        $events = array($this->getMock('CalendR\Event\EventInterface'));
        $period = $this->getMock('CalendR\Period\PeriodInterface');
        $this->calendar
            ->expects($this->once())
            ->method('getEvents')
            ->with($period)
            ->will($this->returnValue($events));
        $this->assertSame($events, $this->object->getEvents($period));
    }
}
