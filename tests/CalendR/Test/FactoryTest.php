<?php

namespace CalendR\Test;

use CalendR\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMonth()
    {
        $factory = new Factory;

        $month = $factory->getMonth(new \DateTime('2012-01-01'));
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);

        $month = $factory->getMonth(2012, 01);
        $this->assertInstanceOf('CalendR\\Period\\Month', $month);
    }

    public function testGetWeek()
    {
        $factory = new Factory;

        $week = $factory->getWeek(new \DateTime('2012-W01'));
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);

        $week = $factory->getWeek(2012, 1);
        $this->assertInstanceOf('CalendR\\Period\\Week', $week);
    }
}
