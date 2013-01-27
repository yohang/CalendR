<?php

namespace CalendR\Test\Period;

use CalendR\Period\Week0;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-01')),
            array(new \DateTime('2012-01-08')),
            array(new \DateTime('2012-01-22')),
        );
    }
    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Week0($start);
    }
}
