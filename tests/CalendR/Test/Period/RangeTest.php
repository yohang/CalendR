<?php

namespace CalendR\Test\Period;

use CalendR\Period\Range;

class RangeTest extends \PHPUnit_Framework_TestCase
{
    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-01'), new \DateTime('2012-01-06'), new \DateTime('2012-01-04'), new \DateTime('2013-02-09')),
            array(new \DateTime('2011-01-03'), new \DateTime('2011-01-11'), new \DateTime('2011-01-05'), new \DateTime('2012-03-19')),
            array(new \DateTime('2012-01-01'), new \DateTime('2013-01-01'), new \DateTime('2012-09-09'), new \DateTime('2011-10-09')),
            array(new \DateTime('2013-02-02'), new \DateTime('2013-02-09'), new \DateTime('2013-02-02'), new \DateTime('2013-02-09')),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($begin, $end, $contain, $notContain)
    {
        $range = new Range($begin, $end);

        $this->assertTrue($range->contains($contain));
        $this->assertFalse($range->contains($notContain));
    }

    public function testGetNext()
    {
        $range = new Range(new \DateTime('2012-01-01'), new \DateTime('2012-01-03'));
        $this->assertEquals('2012-01-03', $range->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious()
    {
        $range = new Range(new \DateTime('2012-01-01'), new \DateTime('2012-01-03'));
        $this->assertEquals('2011-12-30', $range->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod()
    {
        $begin = new \DateTime('2012-01-01');
        $range = new Range($begin, new \DateTime('2012-01-03'));
        foreach ($range->getDatePeriod() as $dateTime) {
            $this->assertEquals($begin->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $begin->add(new \DateInterval('P2D'));
        }
    }

    /**
     * @expectedException \CalendR\Period\Exception\NotImplemented
     */
    public function testGetDateInterval()
    {
        Range::getDateInterval();
    }
}
