<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Factory;
use CalendR\Period\Month;
use CalendR\Period\Week;
use CalendR\Period\Year;
use PHPUnit\Framework\TestCase;

class AlternatePeriodsTest extends TestCase
{
    protected $options = array(
        'day_class'   => 'CalendR\Test\Fixtures\Period\Day',
        'week_class'  => 'CalendR\Test\Fixtures\Period\Week',
        'month_class' => 'CalendR\Test\Fixtures\Period\Month',
        'year_class'  => 'CalendR\Test\Fixtures\Period\Year',
        'range_class' => 'CalendR\Test\Fixtures\Period\Range',
    );

    protected $periodFactory;

    /** @var $calendar Calendar */
    protected $calendar;

    protected function setUp(): void
    {
        $this->calendar = new Calendar();
        $this->calendar->setFactory(new Factory($this->options));
    }

    public function testCalendar()
    {
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Year', $this->calendar->getYear(2013));
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Month', $this->calendar->getMonth(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $this->calendar->getWeek(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Day', $this->calendar->getDay(2013, 1, 1));
    }

    public function testCalendarSetOptions()
    {
        $options = array('week_class' => 'CalendR\Test\Fixtures\Period\Week');
        $calendar = new Calendar;
        $calendar->setFactory(new Factory($options));
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $calendar->getWeek(new \DateTime('2012W01')));
    }

    public function testCalendarSetOption()
    {
        $calendar = new Calendar();
        $calendar->setFactory(new Factory(array('week_class' => 'CalendR\Test\Fixtures\Period\Week')));
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $calendar->getWeek(new \DateTime('2012W01')));
    }

    public function testCalendarGetOption()
    {
        $calendar = new Calendar();
        $this->assertEquals(1, $calendar->getFactory()->getFirstWeekday());
        $calendar->setFactory(new Factory(array('first_weekday' => 0)));
        $this->assertEquals(0, $calendar->getFactory()->getFirstWeekday());
    }

    public function testYear()
    {
        $year = new Year(new \DateTime('2013-01-01'), new Factory($this->options));
        foreach ($year as $month) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Month', $month);
        }
    }

    public function testMonth()
    {
        $month = new Month(new \DateTime('2013-01-01'), new Factory($this->options));
        foreach ($month as $week) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $week);
        }
        $days = $month->getDays();
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Day', $days[0]);
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Range', $month->getExtendedMonth());
    }

    public function testWeek()
    {
        $week = new Week(new \DateTime('2013W01'), new Factory($this->options));
        foreach ($week as $day) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Day', $day);
        }
    }
}
