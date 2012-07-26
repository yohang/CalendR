<?php

namespace CalendR\Test\Period;

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
        );
    }

    public static function providerGetFirstMondayAndLastSunday()
    {
        $factory = new \CalendR\Calendar();

        return array(
            array($factory->getMonth(2012, 1), '2011-12-26', '2012-02-05'),
            array($factory->getMonth(2012, 2), '2012-01-30', '2012-03-04'),
            array($factory->getMonth(2012, 3), '2012-02-27', '2012-04-01'),
            array($factory->getMonth(2012, 9), '2012-08-27', '2012-09-30'),
            array($factory->getMonth(2012, 10), '2012-10-01', '2012-11-04'),
            array($factory->getMonth(2012, 12), '2012-11-26', '2013-01-06'),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $month = new Month($start);

        $this->assertTrue($month->contains($contain));
        $this->assertFalse($month->contains($notContain));
    }

    /**
     * @dataProvider providerGetFirstMondayAndLastSunday
     */
    public function testGetFirstMonday(Month $month, $monday)
    {
        $this->assertSame($monday, $month->getFirstMonday()->format('Y-m-d'));
    }

    /**
     * @dataProvider providerGetFirstMondayAndLastSunday
     */
    public function testGetLastSunday(Month $month, $monday, $sunday)
    {
        $this->assertSame($sunday, $month->getLastSunday()->format('Y-m-d'));
    }


    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAMonth
     */
    public function testConstructInvalid($start)
    {
        new Month($start);
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Month($start);
    }

    public function testIteration()
    {
        $start = new \DateTime('2012-01-01');
        $month = new Month($start);

        $i = 0;

        foreach ($month as $week) {
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

    public function testGetDays()
    {
        $month = new Month(new \DateTime('2012-01-01'));
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
        $month = new Month(new \DateTime('2012-01-01'));
        $this->assertEquals('2012-02-01', $month->getNext()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTime('2012-12-01'));
        $this->assertEquals('2013-01-01', $month->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious()
    {
        $month = new Month(new \DateTime('2012-01-01'));
        $this->assertEquals('2011-12-01', $month->getPrevious()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTime('2012-03-01'));
        $this->assertEquals('2012-02-01', $month->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod()
    {
        $date = new \DateTime('2012-01-01');
        $month = new Month($date);
        foreach ($month->getDatePeriod() as $dateTime) {
            $this->assertEquals($date->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $date->add(new \DateInterval('P1D'));
        }
    }
}
