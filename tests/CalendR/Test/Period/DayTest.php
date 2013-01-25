<?php

namespace CalendR\Test\Period;

use CalendR\Period\Day;

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

    public function testIsCurrent()
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
}
