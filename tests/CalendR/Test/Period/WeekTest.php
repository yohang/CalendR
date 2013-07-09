<?php

namespace CalendR\Test\Period;

use CalendR\Period\Week;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-02')),
            array(new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-23')),
        );
    }

    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-04'), new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-19')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2011-01-10')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-17')),
        );
    }

    public static function providerNumber()
    {
        return array(
            array(new \DateTime('2012-01-02'), 1),
            array(new \DateTime('2012-01-09'), 2),
            array(new \DateTime('2011-12-26'), 52),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $week = new Week($start);

        $this->assertTrue($week->contains($contain));
        $this->assertFalse($week->contains($notContain));
    }

    /**
     * @dataProvider providerNumber
     */
    public function testNumber($start, $number)
    {
        $week = new Week($start);

        $this->assertEquals($week->getNumber(), $number);
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Week($start);
    }

    public function testIteration()
    {
        $start = new \DateTime('2012-W01');
        $week = new Week($start);

        $i = 0;

        foreach ($week as $day) {
            $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));
            $start->add(new \DateInterval('P1D'));
            $i++;
        }

        $this->assertEquals($i, 7);
    }
}
