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
