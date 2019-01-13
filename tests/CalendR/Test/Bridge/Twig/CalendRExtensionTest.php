<?php

namespace CalendR\Test\Bridge\Twig;

use CalendR\Bridge\Twig\CalendRExtension;
use CalendR\Calendar;
use CalendR\Event\EventInterface;
use CalendR\Period\PeriodInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class CalendRExtensionTest extends TestCase
{
    /**
     * @var CalendRExtension
     */
    protected $object;

    /**
     * @var MockObject|Calendar
     */
    protected $calendar;

    protected function setUp()
    {
        $this->calendar = $this->getMockBuilder(Calendar::class)->getMock();
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
        foreach (['year', 'month', 'week', 'day'] as $periodName) {
            $period = $this->getMockBuilder(PeriodInterface::class)->getMock();
            $this->calendar
                ->expects($this->once())
                ->method('get' . ucfirst($periodName))
                ->with('foo', 'bar')
                ->will($this->returnValue($period));

            $this->assertSame($period, $this->object->{'get' . ucfirst($periodName)}('foo', 'bar'));
        }

        $events = [$this->getMockBuilder(EventInterface::class)->getMock()];
        $period = $this->getMockBuilder(PeriodInterface::class)->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getEvents')
            ->with($period)
            ->will($this->returnValue($events));
        $this->assertSame($events, $this->object->getEvents($period));
    }
}
