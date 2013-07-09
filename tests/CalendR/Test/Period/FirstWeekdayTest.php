<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Period\Range;
use CalendR\Period\Week;
use CalendR\Period\Year;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class FirstWeekdayTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultDayIsMonday()
    {
        $calendar = new Calendar;
        $this->assertSame(Day::MONDAY, $calendar->getYear(2013)->getFirstWeekday());
        $this->assertSame(Day::MONDAY, $calendar->getMonth(2013, 1)->getFirstWeekday());
        $this->assertSame(Day::MONDAY, $calendar->getWeek(2013, 1)->getFirstWeekday());
        $this->assertSame(Day::MONDAY, $calendar->getDay(2013, 1, 1)->getFirstWeekday());
        $range = new Range(new \DateTime, new \DateTime('+3 days'));
        $this->assertSame(Day::MONDAY, $range->getFirstWeekday());
    }

    public function testFactoryTransmitDefaultWeekday()
    {
        $calendar = new Calendar;
        $calendar->setFirstWeekday(Day::SUNDAY);
        $this->assertSame(Day::SUNDAY, $calendar->getYear(2013)->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $calendar->getMonth(2013, 1)->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $calendar->getWeek(2013, 1)->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $calendar->getDay(2013, 1, 1)->getFirstWeekday());
    }

    public function testYearTransmitToMonth()
    {
        $year = new Year(new \DateTime('2013-01-01'), Day::SUNDAY);
        $this->assertSame(Day::SUNDAY, $year->getPrevious()->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $year->getNext()->getFirstWeekday());
        foreach ($year as $month) {
            $this->assertSame(Day::SUNDAY, $month->getFirstWeekday());
        }
    }

    public function testMonthTransmitToWeek()
    {
        $month = new Month(new \DateTime('2013-01-01'), Day::SUNDAY);
        $this->assertSame(Day::SUNDAY, $month->getPrevious()->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $month->getNext()->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $month->getExtendedMonth()->getFirstWeekday());
        foreach ($month as $week) {
            $this->assertSame(Day::SUNDAY, $week->getFirstWeekday());
        }
    }

    public function testWeekTransmitToDay()
    {
        $week = new Week(new \DateTime('2013-W01'), Day::SUNDAY);
        $this->assertSame(Day::SUNDAY, $week->getPrevious()->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $week->getNext()->getFirstWeekday());
        foreach ($week as $day) {
            $this->assertSame(Day::SUNDAY, $day->getFirstWeekday());
        }
    }

    public function testDayTransmitToPreviousAndNext()
    {
        $day = new Day(new \DateTime('2013-01-01'), Day::SUNDAY);
        $this->assertSame(Day::SUNDAY, $day->getPrevious()->getFirstWeekday());
        $this->assertSame(Day::SUNDAY, $day->getNext()->getFirstWeekday());
    }

    public function testIterateOnMonth()
    {
        $calendar = new Calendar;
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(Day::MONDAY, (int) $week->getBegin()->format('w'));
        }

        $calendar->setFirstWeekday(Day::SUNDAY);
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(Day::SUNDAY, (int) $week->getBegin()->format('w'));
        }
    }
}
