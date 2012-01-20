<?php

namespace CalendR\Test\Period;

use CalendR\Period\Week;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructInvalid()
    {
        return array(
            array(new \DateTime('2012-01-03'), new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-07'), new \DateTime('2012-01-16')),
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-16')),
        );
    }

    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16')),
            array(new \DateTime('2012-01-23'), new \DateTime('2012-01-30')),
        );
    }

    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-09'), new \DateTime('2012-01-04'), new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16'), new \DateTime('2012-01-09'), new \DateTime('2012-01-19')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16'), new \DateTime('2012-01-09'), new \DateTime('2011-01-09')),
        );
    }

    public static function providerNumber()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-09'), 1),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16'), 2),
            array(new \DateTime('2011-12-26'), new \DateTime('2012-01-02'), 52),
        );
    }


    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $end, $contain, $notContain)
    {
        $week = new Week($start, $end);

        $this->assertTrue($week->contains($contain));
        $this->assertFalse($week->contains($notContain));
    }

    /**
     * @dataProvider providerNumber
     */
    public function testNumber($start, $end, $number)
    {
        $week = new Week($start, $end);

        $this->assertEquals($week->getNumber(), $number);
    }

    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAWeek
     */
    public function testConstructInvalid($start, $end)
    {
        new Week($start, $end);
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start, $end)
    {
        $week = new Week($start, $end);
    }
}
