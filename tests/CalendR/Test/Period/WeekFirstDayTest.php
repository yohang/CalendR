<?php

namespace CalendR\Test\Period;

use CalendR\Period\Week;
use CalendR\Period\Month;
use CalendR\Period\Year;

class WeekFirstDayTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-01'), 0),
            array(new \DateTime('2012-01-09'), 1),
        );
    }
    public static function providerConstructMonth()
    {
        return array(
            array(new \DateTime('2012-01-01'), 0, 0),
            array(new \DateTime('2012-01-01'), 1, 1),
            array(new \DateTime('2012-01-01'), null, 1),
        );
    }
    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructWeekValid($start, $weekFirstDay)
    {
        $periodFactory = new \CalendR\Period\PeriodFactory();
        $periodFactory->setOption('weekFirstDay', $weekFirstDay);
        $week = new Week($start, $periodFactory);
        $this->assertEquals($weekFirstDay, $week->getWeekFirstDay());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testOptionsBackwardsCompatible($start, $weekFirstDay)
    {
        $week = new Week($start, $weekFirstDay);
        $this->assertEquals($weekFirstDay, $week->getWeekFirstDay());
    }

    /**
     * @dataProvider providerConstructMonth
     */
    public function testConstructMonth($start, $options, $expectedFirstDay)
    {
        $month = new Month($start, $options);
        foreach ($month as $weekNum=>$week){
            /** @var $week Week */
            $this->assertEquals($expectedFirstDay, $week->getWeekFirstDay(), $weekNum);
        }
    }

    /**
     * @dataProvider providerConstructMonth
     */
    public function testConstructYear($start, $options, $expectedFirstDay)
    {
        $year = new Year($start, $options);
        foreach ($year as $month){
            foreach ($month as $weekNum=>$week)
            /** @var $week Week */
            $this->assertEquals($expectedFirstDay, $week->getWeekFirstDay(), $weekNum);
        }
    }
}
