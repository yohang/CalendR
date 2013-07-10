<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;

class AlternatePeriodsTest extends \PHPUnit_Framework_TestCase
{
    protected $options = array(
        'day' => 'CalendR\Test\Fixtures\Period\Day',
        'week' => 'CalendR\Test\Fixtures\Period\Week',
        'month' => 'CalendR\Test\Fixtures\Period\Month',
        'year' => 'CalendR\Test\Fixtures\Period\Year',
        'range' => 'CalendR\Test\Fixtures\Period\Range',
    );

    protected $periodFactory;

    /** @var $calendar Calendar */
    protected $calendar;

    protected function setUp()
    {
        $this->calendar = new Calendar();
        $this->calendar->setOptions($this->options);
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
        $options = array('week' => 'CalendR\Test\Fixtures\Period\Week');
        $calendar = new Calendar();
        $calendar->setOptions($options);
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $calendar->getWeek(new \DateTime('2012W01')));
    }

    public function testCalendarSetOption()
    {
        $calendar = new Calendar();
        $calendar->setOption('week', 'CalendR\Test\Fixtures\Period\Week');
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $calendar->getWeek(new \DateTime('2012W01')));
    }

    public function testCalendarGetOption()
    {
        $calendar = new Calendar();
        $this->assertEquals(1, $calendar->getOption('first_day'));
        $calendar->setOption('first_day', 0);
        $this->assertEquals(0, $calendar->getOption('first_day'));
    }

    public function testYear()
    {
        $year = new \CalendR\Period\Year(new \DateTime('2013-01-01'), $this->options);
        foreach ($year as $month) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Month', $month);
        }
    }

    public function testMonth()
    {
        $month = new \CalendR\Period\Month(new \DateTime('2013-01-01'), $this->options);
        foreach ($month as $week) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Week', $week);
        }
        $days = $month->getDays();
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Day', $days[0]);
        $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Range', $month->getExtendedMonth());
    }

    public function testWeek()
    {
        $week = new \CalendR\Period\Week(new \DateTime('2013W01'), $this->options);
        foreach ($week as $day) {
            $this->assertInstanceOf('CalendR\Test\Fixtures\Period\Day', $day);
        }
    }
}
