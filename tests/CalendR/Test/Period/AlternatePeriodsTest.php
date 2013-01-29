<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\PeriodFactory;

class AlternatePeriodsTest extends \PHPUnit_Framework_TestCase
{
    protected $classes = array(
         'dayClass'   => 'CalendR\Test\Period\AlternatePeriod\Day',
         'weekClass'  => 'CalendR\Test\Period\AlternatePeriod\Week',
         'monthClass' => 'CalendR\Test\Period\AlternatePeriod\Month',
         'yearClass'  => 'CalendR\Test\Period\AlternatePeriod\Year',
    );

    protected $periodFactory;

    /** @var $calendar Calendar */
    protected $calendar;

    protected function setUp()
    {
        $this->periodFactory = new PeriodFactory();
        $this->periodFactory->setOptions($this->classes);
        $this->calendar = new Calendar($this->periodFactory);
    }

    public function testCalendar()
    {
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Year', $this->calendar->getYear(2013));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Month', $this->calendar->getMonth(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Week', $this->calendar->getWeek(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $this->calendar->getDay(2013, 1, 1));
    }

    public function testYear()
    {
        $year = new \CalendR\Period\Year(new \DateTime('2013-01-01'), $this->periodFactory);
        foreach ($year as $month) {
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Month', $month);
        }
    }

    public function testMonth()
    {
        $month = new \CalendR\Period\Month(new \DateTime('2013-01-01'), $this->periodFactory);
        foreach ($month as $week){
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Week', $week);
        }
        $days = $month->getDays();
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $days[0]);
    }

    public function testWeek()
    {
        $week = new \CalendR\Period\Week(new \DateTime('2013W01'), $this->periodFactory);
        foreach ($week as $day) {
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $day);
        }
    }
}
