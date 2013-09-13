<?php

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;

class DayTest extends \PHPUnit_Framework_TestCase
{
    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-02 00:01'), new \DateTime('2012-01-03')),
            array(new \DateTime('2012-05-30'), new \DateTime('2012-05-30 12:25'), new \DateTime('2012-05-29')),
            array(new \DateTime('2012-09-09'), new \DateTime('2012-09-09 23:59'), new \DateTime('2011-09-09')),
            array(new \DateTime('2013-02-02'), new \DateTime('2013-02-02'), new \DateTime('2013-02-03')),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $day = new Day($start);

        $this->assertTrue($day->contains($contain));
        $this->assertFalse($day->contains($notContain));
    }

    public function testGetNext()
    {
        $day = new Day(new \DateTime('2012-01-01'));
        $this->assertEquals('2012-01-02', $day->getNext()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTime('2012-01-31'));
        $this->assertEquals('2012-02-01', $day->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious()
    {
        $day = new Day(new \DateTime('2012-01-01'));
        $this->assertEquals('2011-12-31', $day->getPrevious()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTime('2012-01-31'));
        $this->assertEquals('2012-01-30', $day->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod()
    {
        $day = new Day(new \DateTime('2012-01-31'));
        foreach ($day->getDatePeriod() as $dateTime) {
            $this->assertEquals('2012-01-31', $dateTime->format('Y-m-d'));
        }
    }

    public function testCurrentDay()
    {
        $currentDate = new \DateTime();
        $otherDate = clone $currentDate;
        $otherDate->add(new \DateInterval('P5D'));

        $currentDay = new Day(new \DateTime(date('Y-m-d')));
        $otherDay = $currentDay->getNext();

        $this->assertTrue($currentDay->contains($currentDate));
        $this->assertFalse($currentDay->contains($otherDate));
        $this->assertFalse($otherDay->contains($currentDate));
    }

    public function testToString()
    {
        $day = new Day(new \DateTime(date('Y-m-d')));
        $this->assertSame($day->getBegin()->format('l'), (string)$day);
    }

    public function testIsValid()
    {
        $this->assertSame(true, Day::isValid(new \DateTime));
    }

    /**
     * @dataProvider includesDataProvider
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $day = new Day($begin);
        $this->assertSame($result, $day->includes($period, $strict));
    }

    public function testFormat()
    {
        $day = new Day(new \DateTime);

        $this->assertSame(date('Y-m-d'), $day->format('Y-m-d'));
    }

    public function testIsCurrent()
    {
        $currentDay = new Day(new \DateTime);
        $otherDay   = new Day(new \DateTime('1988-11-12'));

        $this->assertTrue($currentDay->isCurrent());
        $this->assertFalse($otherDay->isCurrent());
    }

    public function includesDataProvider()
    {
        return array(
            array(new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01')), true, false),
            array(new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01')), false, true),
            array(new \DateTime('2013-09-01'), new Day(new \DateTime('2013-09-01')), true, true),
        );
    }
}
