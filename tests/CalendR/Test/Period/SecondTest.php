<?php

namespace CalendR\Test\Period;

use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\Month;
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
        new Second($start);
    }

    /**
     * Test: Valid Constructor
     *
     * @access public
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotASecond
     * @return void
     */
    public function testConstructInvalidStrict($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(true);
        new Second($start, $calendar->getFactory());
    }

    /**
     * Test: Valid Constructor
     *
     * @access public
     * @dataProvider providerConstructInvalid
     * @return void
     */
    public function testConstructInvalidLazy($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(false);
        new Second($start, $calendar->getFactory());
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
        $second = new Second($start);
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
        $second = new Second(new \DateTime('2012-01-01 05:00'));
        $this->assertEquals('2012-01-01 05:00:01', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 14:59:59'));
        $this->assertEquals('2012-01-31 15:00:00', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2013-02-28 23:59:59'));
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
        $second = new Second(new \DateTime('2012-01-01 00:00'));
        $this->assertEquals('2011-12-31 23:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 05:00'));
        $this->assertEquals('2012-01-31 04:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
        $second = new Second(new \DateTime('2012-01-31 05:25:41'));
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
        $second = new Second(new \DateTime('2012-01-31 13:12:27'));
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
        $currentSecond = new Second(new \DateTime(date('Y-m-d H:i:s')));
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
        $second = new Second(new \DateTime(date('Y-m-d H:i:s')));
        $this->assertSame($second->getBegin()->format('s'), (string) $second);
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
        $second = new Second($begin);
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
        $second = new Second(new \DateTime(date('Y-m-d 15:00:27')));
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
        $currentSecond = new Second(new \DateTime(date('Y-m-d H:i:s')));
        $otherSecond   = new Second(new \DateTime('1988-11-12 16:00:50'));
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
        return array(
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:34')),    true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:34')),    false,  false),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:00')),    true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:00')),    false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:34:45')), true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:34:45')), false,  false),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:00')), true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:00')), false,  true),
        );
    }
}
