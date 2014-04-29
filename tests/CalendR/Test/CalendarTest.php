<?php

namespace CalendR\Test;

use CalendR\Calendar;
use CalendR\Period\Day;

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

    public function testGetHour()
    {
        $calendar = new Calendar;

        $hour = $calendar->getHour(new \DateTime('2012-01-01 17:00'));
        $this->assertInstanceOf('CalendR\\Period\\Hour', $hour);

        $hour = $calendar->getHour(2012, 1, 1, 17);
        $this->assertInstanceOf('CalendR\\Period\\Hour', $hour);
    }

    public function testGetMinute()
    {
        $calendar = new Calendar;

        $minute = $calendar->getMinute(new \DateTime('2012-01-01 17:23'));
        $this->assertInstanceOf('CalendR\\Period\\Minute', $minute);

        $minute = $calendar->getMinute(2012, 1, 1, 17, 23);
        $this->assertInstanceOf('CalendR\\Period\\Minute', $minute);
    }

    public function testGetSecond()
    {
        $calendar = new Calendar;

        $second = $calendar->getSecond(new \DateTime('2012-01-01 17:23:49'));
        $this->assertInstanceOf('CalendR\\Period\\Second', $second);

        $second = $calendar->getSecond(2012, 1, 1, 17, 23, 49);
        $this->assertInstanceOf('CalendR\\Period\\Second', $second);
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

    public function testGetFirstWeekday()
    {
        $calendar = new Calendar;
        $factory  = $this->getMock('CalendR\Period\FactoryInterface');
        $calendar->setFactory($factory);
        $factory->expects($this->once())->method('getFirstWeekday')->will($this->returnValue(Day::SUNDAY));

        $this->assertSame(Day::SUNDAY, $calendar->getFirstWeekday());
    }

    /**
     * @dataProvider weekAndWeekdayProvider
     */
    public function testGetWeekWithWeekdayConfiguration($year, $week, $weekday, $day)
    {
        $calendar = new Calendar;
        $calendar->getFactory()->setFirstWeekday($weekday);
        $week     = $calendar->getWeek($year, $week);

        $this->assertEquals($weekday, $week->format('w'));
        $this->assertSame($day, $week->format('Y-m-d'));
    }

    public function testGetEventManager()
    {
        $calendar = new Calendar;
        $this->assertInstanceOf('CalendR\Event\Manager', $calendar->getEventManager());
    }

    public static function weekAndWeekdayProvider()
    {
        return array(
            array(2013, 1, Day::MONDAY, '2012-12-31'),
            array(2013, 1, Day::TUESDAY, '2012-12-25'),
            array(2013, 1, Day::WEDNESDAY, '2012-12-26'),
            array(2013, 1, Day::THURSDAY, '2012-12-27'),
            array(2013, 1, Day::FRIDAY, '2012-12-28'),
            array(2013, 1, Day::SATURDAY, '2012-12-29'),
            array(2013, 1, Day::SUNDAY, '2012-12-30'),

            array(2013, 8, Day::MONDAY, '2013-02-18'),
            array(2013, 8, Day::TUESDAY, '2013-02-12'),
            array(2013, 8, Day::WEDNESDAY, '2013-02-13'),
            array(2013, 8, Day::THURSDAY, '2013-02-14'),
            array(2013, 8, Day::FRIDAY, '2013-02-15'),
            array(2013, 8, Day::SATURDAY, '2013-02-16'),
            array(2013, 8, Day::SUNDAY, '2013-02-17'),
        );
    }
    
    public function testStrictDates()
    {
        // When we start, the option "strict_dates" should be set to false.
        $calendar = new Calendar;
        $this->assertSame(false, $calendar->getStrictDates());
        $calendar->setStrictDates(true);
        $this->assertSame(true, $calendar->getStrictDates());
    }
}
