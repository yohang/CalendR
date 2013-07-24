<?php

namespace CalendR\Test\Period;

use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDay()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Day',
            $this->getDefaultOptionsFactory()->createDay(new \DateTime('2012-01-01'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Day',
            $this->getAlternateOptionsFactory()->createDay(new \DateTime('2012-01-01'))
        );
    }

    public function testCreateWeek()
    {
        $this->assertInstanceOf('CalendR\Period\Week', $this->getDefaultOptionsFactory()->createWeek(new \DateTime('2012-W01')));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Week',
            $this->getAlternateOptionsFactory()->createWeek(new \DateTime('2012-W01'))
        );
    }

    public function testCreateMonth()
    {
        $this->assertInstanceOf('CalendR\Period\Month', $this->getDefaultOptionsFactory()->createMonth(new \DateTime('2012-01-01')));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Month',
            $this->getAlternateOptionsFactory()->createMonth(new \DateTime('2012-01-01'))
        );
    }

    public function testCreateYear()
    {
        $this->assertInstanceOf('CalendR\Period\Year', $this->getDefaultOptionsFactory()->createYear(new \DateTime('2012-01-01')));
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Year',
            $this->getAlternateOptionsFactory()->createYear(new \DateTime('2012-01-01'))
        );
    }

    public function testCreateRange()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Range',
            $this->getDefaultOptionsFactory()->createRange(new \DateTime('2012-01-01'), new \DateTime('2012-01-02'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Range',
            $this->getAlternateOptionsFactory()->createRange(new \DateTime('2012-01-01'), new \DateTime('2012-01-02'))
        );
    }

    /**
     * @return FactoryInterface
     */
    protected function getDefaultOptionsFactory()
    {
        return new Factory;
    }

    /**
     * @return FactoryInterface
     */
    protected function getAlternateOptionsFactory()
    {
        return new Factory(
            array(
                'day_class'   => 'CalendR\Test\Fixtures\Period\Day',
                'month_class' => 'CalendR\Test\Fixtures\Period\Month',
                'range_class' => 'CalendR\Test\Fixtures\Period\Range',
                'week_class'  => 'CalendR\Test\Fixtures\Period\Week',
                'year_class'  => 'CalendR\Test\Fixtures\Period\Year',
            )
        );
    }
}
