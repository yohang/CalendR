<?php

namespace CalendR\Test;

use CalendR\Calendar;

class CalendarTest extends \PHPUnit_Framework_TestCase
{
    public function testGetYear()
    {
        $calendar = new Calendar;

        $year = $calendar->getYear(new \DateTime('2012-01'));
        $this->assertInstanceOf('CalendR\\Period\\Year', $year);

        $year = $calendar->getYear(2012);
        $this->assertInstanceOf('CalendR\\Period\\Year', $year);
    }

    public function testGetMonth()
    {
        $calendar = new Calendar;

        $month = $calendar->getMonth(new \DateTime('2012-01-01'));
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);

        $month = $calendar->getMonth(2012, 01);
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);
    }

    public function testGetWeek()
    {
        $calendar = new Calendar;

        $week = $calendar->getWeek(new \DateTime('2012-W01'));
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);

        $week = $calendar->getWeek(2012, 1);
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);
    }

    public function testGetDay()
    {
        $calendar = new Calendar;

        $day = $calendar->getDay(new \DateTime('2012-01-01'));
        $this->assertInstanceOf('CalendR\\Period\\Day', $day);

        $day = $calendar->getDay(2012, 1, 1);
        $this->assertInstanceOf('CalendR\\Period\\Day', $day);
    }
}
