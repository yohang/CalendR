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

class MinuteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Data Provider: Invalid Constructor
     *
     * @static
     * @access public
     * @return array
     */
    public static function providerConstructInvalid()
    {
        return array(
            array(new \DateTime('2014-12-10 17:30:34')),
            array(new \DateTime('2014-12-10 00:00:01')),
        );
    }

    /**
     * Data Provider: Valid Constructor
     *
     * @static
     * @access public
     * @return array
     */
    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-03')),
            array(new \DateTime('2011-12-10 17:45')),
            array(new \DateTime('2013-07-13 00:00:00')),
        );
    }

    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAMinute
     */
    public function testConstructInvalid($start)
    {
        new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start)
    {
        new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    public static function providerContains()
    {
        return array(
            array(
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-03')
            ),
            array(
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02 00:00:34'),
                new \DateTime('2012-01-02 00:01:00')
            ),
            array(
                new \DateTime('2012-05-30 05:23'),
                new \DateTime('2012-05-30 05:23:23'),
                new \DateTime('2012-05-30')
            ),
        );
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain)
    {
        $minute = new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($minute->contains($contain));
        $this->assertFalse($minute->contains($notContain));
    }

    public function testGetNext()
    {
        $minute = new Minute(new \DateTime('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 05:01', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 14:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2013-02-28 23:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetPrevious()
    {
        $minute = new Minute(new \DateTime('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 05:25'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:24', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetDatePeriod()
    {
        $minute = new Minute(new \DateTime('2012-01-31 13:12'), $this->prophesize(FactoryInterface::class)->reveal());
        $i = 0;
        foreach ($minute->getDatePeriod() as $dateTime) {
            $i++;
            $this->assertEquals('2012-01-31 13:12', $dateTime->format('Y-m-d H:i'));
        }
        $this->assertSame(60, $i);
    }

    public function testCurrentMinute()
    {
        $currentDateTime = new \DateTime();
        $otherDateTime = clone $currentDateTime;
        $otherDateTime->add(new \DateInterval('PT5M'));
        $currentMinute = new Minute(new \DateTime(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMinute = $currentMinute->getNext();
        $this->assertTrue($currentMinute->contains($currentDateTime));
        $this->assertFalse($currentMinute->contains($otherDateTime));
        $this->assertFalse($otherMinute->contains($currentDateTime));
    }

    public function testToString()
    {
        $minute = new Minute(new \DateTime(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($minute->getBegin()->format('i'), (string)$minute);
    }

    public function testIsValid()
    {
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05')));
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05 18:00')));
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05 18:36')));
        $this->assertSame(false, Minute::isValid(new \DateTime('2014-03-05 18:36:15')));
    }

    /**
     * @dataProvider providerIncludes
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $minute = new Minute($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $minute->includes($period, $strict));
    }

    public function testFormat()
    {
        $minute = new Minute(new \DateTime(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d H:00'), $minute->format('Y-m-d H:i'));
    }

    public function testIsCurrent()
    {
        $currentMinute = new Minute(new \DateTime(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMinute = new Minute(new \DateTime('1988-11-12 16:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($currentMinute->isCurrent());
        $this->assertFalse($otherMinute->isCurrent());
    }

    public function providerIncludes()
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return array(
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:00'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), false, false),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:30'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:30'), $factory), false, true),
        );
    }

    public function testIteration()
    {
        $start = new \DateTime('2012-01-15 15:47');
        $minute = new Minute($start, new Factory());
        $i = 0;
        foreach ($minute as $secondKey => $second) {
            $this->assertTrue(is_int($secondKey) && $secondKey >= 0 && $secondKey < 60);
            $this->assertInstanceOf('CalendR\\Period\\Second', $second);
            $this->assertSame($start->format('Y-m-d H:i:s'), $second->getBegin()->format('Y-m-d H:i:s'));
            $start->add(new \DateInterval('PT1S'));
            $i++;
        }
        $this->assertEquals($i, 60);
    }
}
