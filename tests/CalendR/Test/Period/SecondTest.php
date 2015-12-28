<?php

namespace CalendR\Test\Period;

use CalendR\Period\FactoryInterface;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;

class SecondTest extends \PHPUnit_Framework_TestCase
{

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
            array(new \DateTime('2013-07-13 12:34:56')),
        );
    }

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
            // Note that an instance of DateTime with no constructor arguments does not contain microseconds.
            array(new \DateTime('2014-05-25 17:45:03.167438')),
        );
    }

    /**
     * Test: Valid Constructor
     *
     * @access public
     * @dataProvider providerConstructValid
     * @return void
     */
    public function testConstructValid($start)
    {
        new Second($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotASecond
     */
    public function testConstructInvalid($start)
    {
        new Second($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * Data Provider: Contains
     *
     * @static
     * @access public
     * @return array
     */
    public static function providerContains()
    {
        return array(
            array(
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-03')
            ),
            array(
                new \DateTime('2012-01-02 05:23'),
                new \DateTime('2012-01-02 05:23:00'),
                new \DateTime('2012-01-02 05:23:01')
            ),
            array(
                new \DateTime('2012-05-30 05:23:14'),
                new \DateTime('2012-05-30 05:23:14'),
                new \DateTime('2012-05-30 05:23:13')
            ),
        );
    }

    /**
     * Test: Contains
     *
     * @access public
     * @dataProvider providerContains
     * @return void
     */
    public function testContains($start, $contain, $notContain)
    {
        $second = new Second($start, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($second->contains($contain));
        $this->assertFalse($second->contains($notContain));
    }

    /**
     * Test: Get Next
     *
     * @access public
     * @return void
     */
    public function testGetNext()
    {
        $second = new Second(new \DateTime('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 05:00:01', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 14:59:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00:00', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2013-02-28 23:59:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00:00', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
    }

    /**
     * Test: Get Previous
     *
     * @access public
     * @return void
     */
    public function testGetPrevious()
    {
        $second = new Second(new \DateTime('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 05:25:41'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:25:40', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
    }

    /**
     * Test: Get Date Period
     *
     * @access public
     * @return void
     */
    public function testGetDatePeriod()
    {
        $second = new Second(new \DateTime('2012-01-31 13:12:27'), $this->prophesize(FactoryInterface::class)->reveal());
        $i = 0;
        foreach ($second->getDatePeriod() as $dateTime) {
            $i++;
            $this->assertEquals('2012-01-31 13:12:27', $dateTime->format('Y-m-d H:i:s'));
        }
        $this->assertSame(1, $i);
    }

    /**
     * Test: Current Second
     *
     * @access public
     * @return void
     */
    public function testCurrentSecond()
    {
        $currentDateTime = new \DateTime();
        $otherDateTime = clone $currentDateTime;
        $otherDateTime->add(new \DateInterval('PT5S'));
        $currentSecond = new Second(new \DateTime(date('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherSecond = $currentSecond->getNext();
        $this->assertTrue($currentSecond->contains($currentDateTime));
        $this->assertFalse($currentSecond->contains($otherDateTime));
        $this->assertFalse($otherSecond->contains($currentDateTime));
    }

    /**
     * Test: To String
     *
     * @access public
     * @return void
     */
    public function testToString()
    {
        $second = new Second(new \DateTime(date('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($second->getBegin()->format('s'), (string)$second);
    }

    /**
     * Test: Is Valid
     *
     * @access public
     * @return void
     */
    public function testIsValid()
    {
        $this->assertSame(true, Second::isValid(new \DateTime));
        $this->assertSame(true, Second::isValid(new \DateTime('2014-03-05')));
        $this->assertSame(true, Second::isValid(new \DateTime('2014-03-05 18:00')));
        $this->assertSame(true, Second::isValid(new \DateTime('2014-03-05 18:36')));
        $this->assertSame(true, Second::isValid(new \DateTime('2014-03-05 18:36:15')));
    }

    /**
     * Test: Includes
     *
     * @access public
     * @dataProvider providerIncludes
     * @return void
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $second = new Second($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $second->includes($period, $strict));
    }

    /**
     * Test: Format
     *
     * @access public
     * @return void
     */
    public function testFormat()
    {
        $second = new Second(new \DateTime(date('Y-m-d 15:00:27')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d 15:00:27'), $second->format('Y-m-d H:i:s'));
    }

    /**
     * Test: Is Current
     *
     * @access public
     * @return void
     */
    public function testIsCurrent()
    {
        $currentSecond = new Second(new \DateTime(date('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherSecond = new Second(new \DateTime('1988-11-12 16:00:50'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($currentSecond->isCurrent());
        $this->assertFalse($otherSecond->isCurrent());
    }

    /**
     * Data Provider: Includes
     *
     * @access public
     * @return array
     */
    public function providerIncludes()
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return array(
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), false, false),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:00'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:00'), $factory), false, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), true, false),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, false),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), true, true),
            array(new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), false, true),
        );
    }
}
