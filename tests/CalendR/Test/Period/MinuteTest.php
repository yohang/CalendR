<?php

namespace CalendR\Test\Period;

use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\Month;
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
     * Test: Invalid Constructor (Strict)
     *
     * @access public
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAMinute
     * @return void
     */
    public function testConstructInvalidStrict($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(true);
        new Minute($start, $calendar->getFactory());
    }

    /**
     * Test: Invalid Constructor (Lazy)
     *
     * @access public
     * @dataProvider providerConstructInvalid
     * @return void
     */
    public function testConstructInvalidLazy($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(false);
        new Minute($start, $calendar->getFactory());
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
        new Minute($start);
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
     * Test: Contains
     *
     * @access public
     * @dataProvider providerContains
     * @return void
     */
    public function testContains($start, $contain, $notContain)
    {
        $minute = new Minute($start);
        $this->assertTrue($minute->contains($contain));
        $this->assertFalse($minute->contains($notContain));
    }

    /**
     * Test: Get Next
     *
     * @access public
     * @return void
     */
    public function testGetNext()
    {
        $minute = new Minute(new \DateTime('2012-01-01 05:00'));
        $this->assertEquals('2012-01-01 05:01', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 14:59'));
        $this->assertEquals('2012-01-31 15:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2013-02-28 23:59'));
        $this->assertEquals('2013-03-01 00:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
    }

    /**
     * Test: Get Previous
     *
     * @access public
     * @return void
     */
    public function testGetPrevious()
    {
        $minute = new Minute(new \DateTime('2012-01-01 00:00'));
        $this->assertEquals('2011-12-31 23:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 05:00'));
        $this->assertEquals('2012-01-31 04:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $minute = new Minute(new \DateTime('2012-01-31 05:25'));
        $this->assertEquals('2012-01-31 05:24', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
    }

    /**
     * Test: Get Date Period
     *
     * @access public
     * @return void
     */
    public function testGetDatePeriod()
    {
        $minute = new Minute(new \DateTime('2012-01-31 13:12'));
        $i = 0;
        foreach ($minute->getDatePeriod() as $dateTime) {
            $i++;
            $this->assertEquals('2012-01-31 13:12', $dateTime->format('Y-m-d H:i'));
        }
        $this->assertSame(60, $i);
    }

    /**
     * Test: Current Minute
     *
     * @access public
     * @return void
     */
    public function testCurrentMinute()
    {
        $currentDateTime = new \DateTime();
        $otherDateTime = clone $currentDateTime;
        $otherDateTime->add(new \DateInterval('PT5M'));
        $currentMinute = new Minute(new \DateTime(date('Y-m-d H:i')));
        $otherMinute = $currentMinute->getNext();
        $this->assertTrue($currentMinute->contains($currentDateTime));
        $this->assertFalse($currentMinute->contains($otherDateTime));
        $this->assertFalse($otherMinute->contains($currentDateTime));
    }

    /**
     * Test: To String
     *
     * @access public
     * @return void
     */
    public function testToString()
    {
        $minute = new Minute(new \DateTime(date('Y-m-d H:i')));
        $this->assertSame($minute->getBegin()->format('i'), (string) $minute);
    }

    /**
     * Test: Is Valid
     *
     * @access public
     * @return void
     */
    public function testIsValid()
    {
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05')));
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05 18:00')));
        $this->assertSame(true, Minute::isValid(new \DateTime('2014-03-05 18:36')));
        $this->assertSame(false, Minute::isValid(new \DateTime('2014-03-05 18:36:15')));
    }

    /**
     * Test: Includes
     *
     * @access public
     * @dataProvider providerIncludes
     * @param DateTime $begin
     * @param PeriodInterface $period
     * @param boolean $strict
     * @param boolean $result
     * @return void
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $minute = new Minute($begin);
        $this->assertSame($result, $minute->includes($period, $strict));
    }

    /**
     * Test: Format
     *
     * @access public
     * @return void
     */
    public function testFormat()
    {
        $minute = new Minute(new \DateTime(date('Y-m-d H:00')));
        $this->assertSame(date('Y-m-d H:00'), $minute->format('Y-m-d H:i'));
    }

    /**
     * Test: Is Current?
     *
     * @access public
     * @return void
     */
    public function testIsCurrent()
    {
        $currentMinute = new Minute(new \DateTime(date('Y-m-d H:i')));
        $otherMinute   = new Minute(new \DateTime('1988-11-12 16:00'));
        $this->assertTrue($currentMinute->isCurrent());
        $this->assertFalse($otherMinute->isCurrent());
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
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:00')),    true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:34')),    false,  false),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:00')), true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:00')), false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:30')), true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:00:30')), false,  true),
        );
    }

    /**
     * Test: Iteration (Iterator Interface)
     *
     * @access public
     * @return void
     */
    public function testIteration()
    {
        $start = new \DateTime('2012-01-15 15:47');
        $minute = new Minute($start);
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
