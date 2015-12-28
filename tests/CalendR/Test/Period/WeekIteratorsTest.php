<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Factory;
use CalendR\Period\Month;
use CalendR\Period\Week;
use CalendR\Period\Year;

class WeekIteratorsTest extends \PHPUnit_Framework_TestCase
{
    /** @var $calendar Calendar */
    protected $calendar;

    protected function setUp()
    {
        $this->calendar = new Calendar();
    }

    public static function providerIteratorOptions()
    {
        return array(
            array(array(), 'CalendR\Period\WeekIterator'),
            array(array('weekdays_only'=> true), 'CalendR\Period\WeekDaysIterator'),
        );
    }

    /**
     * @dataProvider providerIteratorOptions
     */
    public function testWeekDaysOnlyOption($options, $iteratorClass)
    {
        $calendar = new Calendar;
        $calendar->setFactory(new Factory($options));
        /* @var $week Week */
        $week = $calendar->getWeek(new \DateTime('2012W01'));
        $this->assertInstanceOf($iteratorClass, $week->getIterator());
    }

    /**
     * @dataProvider providerIteratorOptions
     */
    public function testYear($options, $iteratorClass)
    {
        $year = new Year(new \DateTime('2013-01-01'), new Factory($options));
        foreach ($year as $month) {
            $this->assertInstanceOf('CalendR\Period\Month', $month);
            /* @var $week Week */
            foreach ($month as $week) {
                $this->assertInstanceOf('CalendR\Period\Week', $week);
                $this->assertInstanceOf($iteratorClass, $week->getIterator());
            }
        }
    }

    /**
     * @dataProvider providerIteratorOptions
     */
    public function testMonth($options, $iteratorClass)
    {
        $month = new Month(new \DateTime('2013-01-01'), new Factory($options));
        /* @var $week Week */
        foreach ($month as $week) {
            $this->assertInstanceOf('CalendR\Period\Week', $week);
            $this->assertInstanceOf($iteratorClass, $week->getIterator());
        }
    }

    /**
     * @dataProvider providerIteratorOptions
     */
    public function testWeek($options, $iteratorClass)
    {
        $week = new Week(new \DateTime('2013W01'), new Factory($options));
        foreach ($week as $day) {
            $this->assertInstanceOf('CalendR\Period\Day', $day);
            $this->assertInstanceOf($iteratorClass, $week->getIterator());
        }
    }
}
