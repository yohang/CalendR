<?php

namespace CalendR\Test\Period;

use CalendR\Period\Factory;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDay()
    {
        $this->assertInstanceOf('CalendR\Period\Day', Factory::createDay(new \DateTime('2012-01-01')));
        $this->assertInstanceOf('CalendR\Period\Day', Factory::createDay(2012, 1, 1));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Day',
            Factory::createDay(2012, 1, 1, array('day_class' => 'CalendR\Test\Fixtures\Period\Day'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Day',
            Factory::createDay(new \DateTime('2012-01-01'), array('day_class' => 'CalendR\Test\Fixtures\Period\Day'))
        );
    }

    public function testCreateWeek()
    {
        $this->assertInstanceOf('CalendR\Period\Week', Factory::createWeek(new \DateTime('2012-W01')));
        $this->assertInstanceOf('CalendR\Period\Week', Factory::createWeek(2012, 1));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Week',
            Factory::createWeek(2012, 1, array('week_class' => 'CalendR\Test\Fixtures\Period\Week'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Week',
            Factory::createWeek(new \DateTime('2012-W01'), array('week_class' => 'CalendR\Test\Fixtures\Period\Week'))
        );
    }

    public function testCreateMonth()
    {
        $this->assertInstanceOf('CalendR\Period\Month', Factory::createMonth(new \DateTime('2012-01-01')));
        $this->assertInstanceOf('CalendR\Period\Month', Factory::createMonth(2012, 1));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Month',
            Factory::createMonth(2012, 1, array('month_class' => 'CalendR\Test\Fixtures\Period\Month'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Month',
            Factory::createMonth(new \DateTime('2012-01-01'), array('month_class' => 'CalendR\Test\Fixtures\Period\Month'))
        );
    }

    public function testCreateYear()
    {
        $this->assertInstanceOf('CalendR\Period\Year', Factory::createYear(new \DateTime('2012-01-01')));
        $this->assertInstanceOf('CalendR\Period\Year', Factory::createYear(2012));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Year',
            Factory::createYear(2012, array('year_class' => 'CalendR\Test\Fixtures\Period\Year'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Year',
            Factory::createYear(new \DateTime('2012-01-01'), array('year_class' => 'CalendR\Test\Fixtures\Period\Year'))
        );
    }

    public function testCreateRange()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Range',
            Factory::createRange(new \DateTime('2012-01-01'), new \DateTime('2012-01-02'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Range',
            Factory::createRange(
                new \DateTime('2012-01-01'),
                new \DateTime('2012-01-02'),
                array('range_class' => 'CalendR\Test\Fixtures\Period\Range')
            )
        );
    }
}
