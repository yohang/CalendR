<?php

namespace CalendR\Test\Period;

use CalendR\Period\Week;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    public static function providerContains()
    {
        return array(
            array(new \DateTime('2012-01-02'), new \DateTime('2012-01-09'), new \DateTime('2012-01-04'), new \DateTime('2012-01-09')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16'), new \DateTime('2012-01-09'), new \DateTime('2012-01-19')),
            array(new \DateTime('2012-01-09'), new \DateTime('2012-01-16'), new \DateTime('2012-01-09'), new \DateTime('2011-01-09')),
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
}