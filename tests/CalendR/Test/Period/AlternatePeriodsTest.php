<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;

class AlternatePeriodsTest extends \PHPUnit_Framework_TestCase
{
    protected $classes = array(
         'dayClass'   => 'CalendR\Test\Period\AlternatePeriod\Day',
         'weekClass'  => 'CalendR\Test\Period\AlternatePeriod\Week',
         'monthClass' => 'CalendR\Test\Period\AlternatePeriod\Month',
         'yearClass'  => 'CalendR\Test\Period\AlternatePeriod\Year',
    );

    /** @var $factory Calendar */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new Calendar();
        $this->factory->setClasses($this->classes);
    }

    public function testFactory()
    {
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Year', $this->factory->getYear(2013));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Month', $this->factory->getMonth(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Week', $this->factory->getWeek(2013, 1));
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $this->factory->getDay(2013, 1, 1));
    }

    public function testYear()
    {
        $year = new \CalendR\Period\Year(new \DateTime('2013-01-01'), 1, $this->classes);
        foreach ($year as $month) {
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Month', $month);
        }
    }

    public function testMonth()
    {
        $month = new \CalendR\Period\Month(new \DateTime('2013-01-01'), 1, $this->classes);
        foreach ($month as $week){
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Week', $week);
        }
        $days = $month->getDays();
        $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $days[0]);
    }

    public function testWeek()
    {
        $week = new \CalendR\Period\Week(new \DateTime('2013W01'), 1, $this->classes);
        foreach ($week as $day) {
            $this->assertInstanceOf('CalendR\Test\Period\AlternatePeriod\Day', $day);
        }
    }
}
