<?php

namespace CalendR\Test\Bridge\Twig;

use CalendR\Bridge\Twig\CalendRExtension;
use CalendR\Calendar;
use CalendR\Event\Collection\Basic;
use CalendR\Event\EventInterface;
use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Week;
use CalendR\Period\Year;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class CalendRExtensionTest extends TestCase
{
    protected CalendRExtension $object;

    protected Calendar $calendar;

    protected function setUp(): void
    {
        $this->calendar = $this->getMockBuilder(Calendar::class)->getMock();
        $this->object   = new CalendRExtension($this->calendar);
    }

    public function testItReturnsFunctionNames(): void
    {

        $functions = array_map(
            static function (TwigFunction $fn) { return $fn->getName(); },
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
        $period = $this->getMockBuilder(Year::class)->disableOriginalConstructor()->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getYear')
            ->with(2021)
            ->willReturn($period);

        $this->assertSame($period, $this->object->getYear(2021));

        $period = $this->getMockBuilder(Month::class)->disableOriginalConstructor()->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getMonth')
            ->with(2021, 12)
            ->willReturn($period);

        $this->assertSame($period, $this->object->getMonth(2021, 12));

        $period = $this->getMockBuilder(Week::class)->disableOriginalConstructor()->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getWeek')
            ->with(2021, 22)
            ->willReturn($period);

        $this->assertSame($period, $this->object->getWeek(2021, 22));

        $period = $this->getMockBuilder(Day::class)->disableOriginalConstructor()->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getDay')
            ->with(1988, 11, 12)
            ->willReturn($period);

        $this->assertSame($period, $this->object->getDay(1988, 11, 12));

        $events = new Basic([$this->getMockBuilder(EventInterface::class)->getMock()]);
        $period = $this->getMockBuilder(PeriodInterface::class)->getMock();
        $this->calendar
            ->expects($this->once())
            ->method('getEvents')
            ->with($period)
            ->willReturn($events);

        $this->assertSame($events, $this->object->getEvents($period));
    }
}
