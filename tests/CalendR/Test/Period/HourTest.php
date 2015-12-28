<?php

namespace CalendR\Test\Period;

use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;

class HourTest extends \PHPUnit_Framework_TestCase
{
    public static function providerConstructInvalid()
    {
        return array(
            array(new \DateTime('2014-12-10 17:30')),
            array(new \DateTime('2014-12-10 00:00:01')),
        );
    }

    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-03')),
            array(new \DateTime('2011-12-10')),
            array(new \DateTime('2013-07-13 00:00:00')),
        );
    }

    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAnHour
     */
    public function testConstructInvalid($start)
    {
        new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @param \DateTime $start
     *
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    public static function providerContains()
    {
        return array(
            array(
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02 00:01'),
                new \DateTime('2012-01-02 12:34')
            ),
            array(
                new \DateTime('2012-05-30 05:00'),
                new \DateTime('2012-05-30 05:00'),
                new \DateTime('2012-05-30 06:00')
            ),
            array(
                new \DateTime('2012-09-09 05:00'),
                new \DateTime('2012-09-09 05:00:01'),
                new \DateTime('2011-08-09 05:30')
            ),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $hour = new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($hour->contains($contain));
        $this->assertFalse($hour->contains($notContain));
    }

    /**
     * Test: Get Next
     *
     * @access public
     * @return void
     */
    public function testGetNext()
    {
        $hour = new Hour(new \DateTime('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 06:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 14:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2013-02-28 23:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
    }

    /**
     * Test: Get Previous
     *
     * @access public
     * @return void
     */
    public function testGetPrevious()
    {
        $hour = new Hour(new \DateTime('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 06:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
    }

    /**
     * Test: Get Date Period
     *
     * @access public
     * @return void
     */
    public function testGetDatePeriod()
    {
        $hour = new Hour(new \DateTime('2012-01-31 13:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $i = 0;
        foreach ($hour->getDatePeriod() as $dateTime) {
            $i++;
            $this->assertEquals('2012-01-31 13', $dateTime->format('Y-m-d H'));
        }
        $this->assertSame(60, $i);
    }

    /**
     * Test: Current Hour
     *
     * @access public
     * @return void
     */
    public function testCurrentHour()
    {
        $currentDateTime = new \DateTime();
        $otherDateTime = clone $currentDateTime;
        $otherDateTime->add(new \DateInterval('PT5H'));
        $currentHour = new Hour(new \DateTime(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherHour = $currentHour->getNext();
        $this->assertTrue($currentHour->contains($currentDateTime));
        $this->assertFalse($currentHour->contains($otherDateTime));
        $this->assertFalse($otherHour->contains($currentDateTime));
    }

    /**
     * Test: To String (Magic Method)
     *
     * @access public
     * @return void
     */
    public function testToString()
    {
        $hour = new Hour(new \DateTime(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($hour->getBegin()->format('G'), (string)$hour);
    }

    /**
     * Test: Is Valid?
     *
     * @access public
     * @return void
     */
    public function testIsValid()
    {
        $this->assertSame(true, Hour::isValid(new \DateTime('2014-03-05')));
        $this->assertSame(true, Hour::isValid(new \DateTime('2014-03-05 18:00')));
        $this->assertSame(false, Hour::isValid(new \DateTime('2014-03-05 18:36')));
        $this->assertSame(false, Hour::isValid(new \DateTime('2014-03-05 18:00:01')));
    }

    /**
     * @dataProvider includesDataProvider
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $hour = new Hour($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $hour->includes($period, $strict));
    }

    public function testFormat()
    {
        $hour = new Hour(new \DateTime(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d H:00'), $hour->format('Y-m-d H:i'));
    }

    public function testIsCurrent()
    {
        $currentHour = new Hour(new \DateTime(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherHour = new Hour(new \DateTime('1988-11-12 16:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($currentHour->isCurrent());
        $this->assertFalse($otherHour->isCurrent());
    }

    public function includesDataProvider()
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return array(
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, true),
        );
    }

    /**
     * Test: Iteration
     *
     * @access public
     * @return void
     */
    public function testIteration()
    {
        $start = new \DateTime('2012-01-15 13:00');
        $hour = new Hour($start, new Factory());
        $i = 0;
        foreach ($hour as $minuteKey => $minute) {
            $this->assertTrue(is_int($minuteKey) && $minuteKey >= 0 && $minuteKey < 60);
            $this->assertInstanceOf('CalendR\\Period\\Minute', $minute);
            $this->assertSame($start->format('Y-m-d H:i'), $minute->getBegin()->format('Y-m-d H:i'));
            $this->assertSame('00', $minute->getBegin()->format('s'));
            $start->add(new \DateInterval('PT1M'));
            $i++;
        }
        $this->assertEquals($i, 60);
    }
}
