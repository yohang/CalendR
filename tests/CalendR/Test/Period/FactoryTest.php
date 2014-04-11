<?php

namespace CalendR\Test\Period;

use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSecond()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Second',
            $this->getDefaultOptionsFactory()->createSecond(new \DateTime('2012-01-01 17:23:49'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Second',
            $this->getAlternateOptionsFactory()->createSecond(new \DateTime('2012-01-01 17:23:49'))
        );
    }

    public function testCreateMinute()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Minute',
            $this->getDefaultOptionsFactory()->createMinute(new \DateTime('2012-01-01 17:23'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Minute',
            $this->getAlternateOptionsFactory()->createMinute(new \DateTime('2012-01-01 17:23'))
        );
    }

    public function testCreateHour()
    {
        $this->assertInstanceOf(
            'CalendR\Period\Hour',
            $this->getDefaultOptionsFactory()->createHour(new \DateTime('2012-01-01 17:00'))
        );
        $this->assertInstanceOf(
            'CalendR\Test\Fixtures\Period\Hour',
            $this->getAlternateOptionsFactory()->createHour(new \DateTime('2012-01-01 17:00'))
        );
    }

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
     * @dataProvider providerGetFirstMondayAndLastSunday
     */
    public function testFindFirstDayOfWeek(Month $month, $firstDay)
    {
        $this->assertSame(
            $firstDay,
            $this->getDefaultOptionsFactory()->findFirstDayOfWeek($month->getBegin())->format('Y-m-d')
        );
    }

    public static function providerGetFirstMondayAndLastSunday()
    {
        $factory = new \CalendR\Calendar();

        return array(
            array($factory->getMonth(2012, 1), '2011-12-26'),
            array($factory->getMonth(2012, 2), '2012-01-30'),
            array($factory->getMonth(2012, 3), '2012-02-27'),
            array($factory->getMonth(2012, 9), '2012-08-27'),
            array($factory->getMonth(2012, 10), '2012-10-01'),
            array($factory->getMonth(2012, 12), '2012-11-26'),
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
                'second_class'   => 'CalendR\Test\Fixtures\Period\Second',
                'minute_class'   => 'CalendR\Test\Fixtures\Period\Minute',
                'hour_class'   => 'CalendR\Test\Fixtures\Period\Hour',
                'day_class'   => 'CalendR\Test\Fixtures\Period\Day',
                'month_class' => 'CalendR\Test\Fixtures\Period\Month',
                'range_class' => 'CalendR\Test\Fixtures\Period\Range',
                'week_class'  => 'CalendR\Test\Fixtures\Period\Week',
                'year_class'  => 'CalendR\Test\Fixtures\Period\Year',
            )
        );
    }
}
