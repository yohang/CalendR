<?php

namespace CalendR\Test;

use CalendR\Calendar;
use CalendR\Period\Day;

class CalendarTest extends \PHPUnit_Framework_TestCase
{
    public function testGetYear()
    {
        $factory = new Calendar;

        $year = $factory->getYear(new \DateTime('2012-01'));
        $this->assertInstanceOf('CalendR\\Period\\Year', $year);

        $year = $factory->getYear(2012);
        $this->assertInstanceOf('CalendR\\Period\\Year', $year);
    }

    public function testGetMonth()
    {
        $factory = new Calendar;

        $month = $factory->getMonth(new \DateTime('2012-01-01'));
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);

        $month = $factory->getMonth(2012, 01);
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);
    }

    public function testGetWeek()
    {
        $factory = new Calendar;

        $week = $factory->getWeek(new \DateTime('2012-W01'));
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);

        $week = $factory->getWeek(2012, 1);
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);
    }

    public function testGetDay()
    {
        $factory = new Calendar;

        $day = $factory->getDay(new \DateTime('2012-01-01'));
        $this->assertInstanceOf('CalendR\\Period\\Day', $day);

        $day = $factory->getDay(2012, 1, 1);
        $this->assertInstanceOf('CalendR\\Period\\Day', $day);
    }

    public function testGetEvents()
    {
        $em       = $this->getMock('CalendR\Event\Manager');
        $period   = $this->getMock('CalendR\Period\PeriodInterface');
        $events   = array($this->getMock('CalendR\Event\EventInterface'));
        $calendar = new Calendar;
        $calendar->setEventManager($em);
        $em->expects($this->once())->method('find')->with($period, array())->will($this->returnValue($events));

        $this->assertSame($events, $calendar->getEvents($period, array()));
    }
}
