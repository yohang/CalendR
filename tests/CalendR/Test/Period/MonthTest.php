<?php

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;

class MonthTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructInvalid()
    {
        return array(
            array(new \DateTime('2012-01-03')),
            array(new \DateTime('2014-12-10')),
        );
    }

    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-01')),
            array(new \DateTime('2011-01-01')),
            array(new \DateTime('2013-04-01')),
        );
    }

    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-01'), new \DateTime('2012-01-04'), new \DateTime('2012-02-09')),
            array(new \DateTime('2011-02-01'), new \DateTime('2011-02-09'), new \DateTime('2012-03-19')),
            array(new \DateTime('2012-09-01'), new \DateTime('2012-09-09'), new \DateTime('2011-09-01')),
            array(new \DateTime('2013-09-01'), new \DateTime('2013-09-01'), new \DateTime('2013-10-01')),
        );
    }

    public static function providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek()
    {
        return array(
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::MONDAY])), '2013-04-29', '2013-06-02'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::TUESDAY])), '2013-04-30', '2013-06-03'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::WEDNESDAY])), '2013-05-01', '2013-06-04'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::THURSDAY])), '2013-04-25', '2013-06-05'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::FRIDAY])), '2013-04-26', '2013-06-06'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::SATURDAY])), '2013-04-27', '2013-05-31'),
            array(new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-04-28', '2013-06-01'),
            array(new Month(new \DateTime('2013-09-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-09-01', '2013-10-05'),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $month = new Month($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($month->contains($contain));
        $this->assertFalse($month->contains($notContain));
    }

    /**
     * @dataProvider providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek
     */
    public function testGetFirstDayOfFirstWeek(Month $month, $firstDay)
    {
        $this->assertSame($firstDay, $month->getFirstDayOfFirstWeek()->format('Y-m-d'));
    }

    /**
     * @dataProvider providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek
     */
    public function testGetLastDayOfLastWeek(Month $month, $firstDay, $lastDay)
    {
        $this->assertSame($lastDay, $month->getLastDayOfLastWeek()->format('Y-m-d'));
    }

    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAMonth
     */
    public function testConstructInvalid($start)
    {
        new Month($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Month($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    public function testIteration()
    {
        $start = new \DateTime('2012-01-01');
        $month = new Month($start, new Factory());

        $i = 0;

        foreach ($month as $weekKey => $week) {
            $this->assertTrue(is_numeric($weekKey) && $weekKey > 0 && $weekKey < 54);
            $this->assertInstanceOf('CalendR\\Period\\Week', $week);
            foreach ($week as $day) {
                if ($month->contains($day->getBegin())) {
                    $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));
                    $start->add(new \DateInterval('P1D'));
                    $i++;
                }
            }
        }

        $this->assertEquals($i, 31);
    }

    public function testToString()
    {
        $date = new \DateTime('2014-02-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($date->format('F'), (string) $month);
    }

    public function testGetDays()
    {
        $month = new Month(new \DateTime('2012-01-01'), new Factory());
        $days = $month->getDays();

        $this->assertEquals(31, count($days));

        $first = $days[0];
        foreach ($days as $day) {
            $this->assertTrue($first->equals($day));
            $first = $first->getNext();
        }
    }

    public function testGetNext()
    {
        $month = new Month(new \DateTime('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-02-01', $month->getNext()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTime('2012-12-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-01-01', $month->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious()
    {
        $month = new Month(new \DateTime('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-01', $month->getPrevious()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTime('2012-03-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-02-01', $month->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod()
    {
        $date = new \DateTime('2012-01-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());
        foreach ($month->getDatePeriod() as $dateTime) {
            $this->assertEquals($date->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $date->add(new \DateInterval('P1D'));
        }
    }

    public function testIsCurrent()
    {
        $currentDate = new \DateTime();
        $otherDate = clone $currentDate;
        $otherDate->add(new \DateInterval('P5M'));

        $currentMonth = new Month(new \DateTime(date('Y-m').'-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMonth = $currentMonth->getNext();

        $this->assertTrue($currentMonth->contains($currentDate));
        $this->assertFalse($currentMonth->contains($otherDate));
        $this->assertFalse($otherMonth->contains($currentDate));
    }
}
