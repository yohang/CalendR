<?php

namespace CalendR\Test;

use CalendR\Calendar;
use CalendR\Event\Collection\Basic;
use CalendR\Period\Day;
use PHPUnit\Framework\TestCase;
use CalendR\Event\EventInterface;
use CalendR\Period\PeriodInterface;
use CalendR\Event\Manager;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Week;
use CalendR\Period\Month;
use CalendR\Period\Year;

class CalendarTest extends TestCase
{
    public function testGetYear(): void
    {
        $calendar = new Calendar;

        $year = $calendar->getYear(new \DateTime('2012-01'));
        $this->assertInstanceOf(Year::class, $year);

        $year = $calendar->getYear(2012);
        $this->assertInstanceOf(Year::class, $year);
    }

    public function testGetMonth(): void
    {
        $calendar = new Calendar;

        $month = $calendar->getMonth(new \DateTime('2012-01-01'));
        $this->assertInstanceOf(Month::class, $month);

        $month = $calendar->getMonth(2012, 01);
        $this->assertInstanceOf(Month::class, $month);
    }

    public function testGetWeek(): void
    {
        $calendar = new Calendar;

        $week = $calendar->getWeek(new \DateTime('2012-W01'));
        $this->assertInstanceOf(Week::class, $week);

        $week = $calendar->getWeek(2012, 1);
        $this->assertInstanceOf(Week::class, $week);
    }

    public function testGetDay(): void
    {
        $calendar = new Calendar;

        $day = $calendar->getDay(new \DateTime('2012-01-01'));
        $this->assertInstanceOf(Day::class, $day);

        $day = $calendar->getDay(2012, 1, 1);
        $this->assertInstanceOf(Day::class, $day);
    }

    public function testGetHour(): void
    {
        $calendar = new Calendar;

        $hour = $calendar->getHour(new \DateTime('2012-01-01 17:00'));
        $this->assertInstanceOf(Hour::class, $hour);

        $hour = $calendar->getHour(2012, 1, 1, 17);
        $this->assertInstanceOf(Hour::class, $hour);
    }

    public function testGetMinute(): void
    {
        $calendar = new Calendar;

        $minute = $calendar->getMinute(new \DateTime('2012-01-01 17:23'));
        $this->assertInstanceOf(Minute::class, $minute);

        $minute = $calendar->getMinute(2012, 1, 1, 17, 23);
        $this->assertInstanceOf(Minute::class, $minute);
    }

    public function testGetSecond(): void
    {
        $calendar = new Calendar;

        $second = $calendar->getSecond(new \DateTime('2012-01-01 17:23:49'));
        $this->assertInstanceOf(Second::class, $second);

        $second = $calendar->getSecond(2012, 1, 1, 17, 23, 49);
        $this->assertInstanceOf(Second::class, $second);
    }

    public function testGetEvents(): void
    {
        $em       = $this->getMockBuilder(Manager::class)->getMock();
        $period   = $this->getMockBuilder(PeriodInterface::class)->getMock();
        $events   = new Basic([$this->getMockBuilder(EventInterface::class)->getMock()]);
        $calendar = new Calendar;
        $calendar->setEventManager($em);
        $em->expects($this->once())->method('find')->with($period, [])->willReturn($events);

        $this->assertSame($events, $calendar->getEvents($period, []));
    }

    public function testGetFirstWeekday(): void
    {
        $calendar = new Calendar;
        $factory  = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $calendar->setFactory($factory);
        $factory->expects($this->once())->method('getFirstWeekday')->willReturn(Day::SUNDAY);

        $this->assertSame(Day::SUNDAY, $calendar->getFirstWeekday());
    }

    /**
     * @dataProvider weekAndWeekdayProvider
     */
    public function testGetWeekWithWeekdayConfiguration(int $year, int $week, int $weekday, string $day): void
    {
        $calendar = new Calendar;
        $calendar->getFactory()->setFirstWeekday($weekday);
        $week = $calendar->getWeek($year, $week);

        $this->assertEquals($weekday, $week->format('w'));
        $this->assertSame($day, $week->format('Y-m-d'));
    }

    public function testGetEventManager(): void
    {
        $calendar = new Calendar;
        $this->assertInstanceOf(Manager::class, $calendar->getEventManager());
    }

    public static function weekAndWeekdayProvider(): array
    {
        return [
            [2013, 1, Day::MONDAY, '2012-12-31'],
            [2013, 1, Day::TUESDAY, '2012-12-25'],
            [2013, 1, Day::WEDNESDAY, '2012-12-26'],
            [2013, 1, Day::THURSDAY, '2012-12-27'],
            [2013, 1, Day::FRIDAY, '2012-12-28'],
            [2013, 1, Day::SATURDAY, '2012-12-29'],
            [2013, 1, Day::SUNDAY, '2012-12-30'],

            [2013, 8, Day::MONDAY, '2013-02-18'],
            [2013, 8, Day::TUESDAY, '2013-02-12'],
            [2013, 8, Day::WEDNESDAY, '2013-02-13'],
            [2013, 8, Day::THURSDAY, '2013-02-14'],
            [2013, 8, Day::FRIDAY, '2013-02-15'],
            [2013, 8, Day::SATURDAY, '2013-02-16'],
            [2013, 8, Day::SUNDAY, '2013-02-17'],
        ];
    }
}
