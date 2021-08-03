<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Factory;
use CalendR\Period\Month;
use CalendR\Period\Week;
use CalendR\Period\Year;
use CalendR\Test\Fixtures\Period\Month as FixtureMonth;
use CalendR\Test\Fixtures\Period\Week as FixtureWeek;
use CalendR\Test\Fixtures\Period\Year as FixtureYear;
use PHPUnit\Framework\TestCase;
use CalendR\Test\Fixtures\Period\Day;
use CalendR\Test\Fixtures\Period\Range;

class AlternatePeriodsTest extends TestCase
{
    protected array $options = [
        'day_class'   => Day::class,
        'week_class'  => FixtureWeek::class,
        'month_class' => FixtureMonth::class,
        'year_class'  => FixtureYear::class,
        'range_class' => Range::class,
    ];

    protected Calendar $calendar;

    protected function setUp(): void
    {
        $this->calendar = new Calendar();
        $this->calendar->setFactory(new Factory($this->options));
    }

    public function testCalendar(): void
    {
        $this->assertInstanceOf(FixtureYear::class, $this->calendar->getYear(2013));
        $this->assertInstanceOf(FixtureMonth::class, $this->calendar->getMonth(2013, 1));
        $this->assertInstanceOf(FixtureWeek::class, $this->calendar->getWeek(2013, 1));
        $this->assertInstanceOf(Day::class, $this->calendar->getDay(2013, 1, 1));
    }

    public function testCalendarSetOptions(): void
    {
        $options  = ['week_class' => FixtureWeek::class];
        $calendar = new Calendar;
        $calendar->setFactory(new Factory($options));
        $this->assertInstanceOf(FixtureWeek::class, $calendar->getWeek(new \DateTimeImmutable('2012W01')));
    }

    public function testCalendarSetOption(): void
    {
        $calendar = new Calendar();
        $calendar->setFactory(new Factory(['week_class' => FixtureWeek::class]));
        $this->assertInstanceOf(FixtureWeek::class, $calendar->getWeek(new \DateTimeImmutable('2012W01')));
    }

    public function testCalendarGetOption(): void
    {
        $calendar = new Calendar();
        $this->assertEquals(1, $calendar->getFactory()->getFirstWeekday());
        $calendar->setFactory(new Factory(['first_weekday' => 0]));
        $this->assertEquals(0, $calendar->getFactory()->getFirstWeekday());
    }

    public function testYear(): void
    {
        $year = new Year(new \DateTimeImmutable('2013-01-01'), new Factory($this->options));
        foreach ($year as $month) {
            $this->assertInstanceOf(FixtureMonth::class, $month);
        }
    }

    public function testMonth(): void
    {
        $month = new Month(new \DateTimeImmutable('2013-01-01'), new Factory($this->options));
        foreach ($month as $week) {
            $this->assertInstanceOf(FixtureWeek::class, $week);
        }
        $days = $month->getDays();
        $this->assertInstanceOf(Day::class, $days[0]);
        $this->assertInstanceOf(Range::class, $month->getExtendedMonth());
    }

    public function testWeek(): void
    {
        $week = new Week(new \DateTimeImmutable('2013W01'), new Factory($this->options));
        foreach ($week as $day) {
            $this->assertInstanceOf(Day::class, $day);
        }
    }
}
