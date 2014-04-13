<?php

namespace CalendR\Test\Period;

use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;

class HourTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Data Provider: Invalid Constructors
     *
     * @static
     * @access public
     * @return array
     */
    public static function providerConstructInvalid()
    {
        return array(
            array(new \DateTime('2014-12-10 17:30')),
            array(new \DateTime('2014-12-10 00:00:01')),
        );
    }

    /**
     * Data Provider: Valid Constructors
     *
     * @static
     * @access public
     * @return array
     */
    public static function providerConstructValid()
    {
        return array(
            array(new \DateTime('2012-01-03')),
            array(new \DateTime('2011-12-10')),
            array(new \DateTime('2013-07-13 00:00:00')),
        );
    }

    /**
     * Test: Invalid Constructors (Strict)
     *
     * @access public
     * @param DateTime $start
     * @dataProvider providerConstructInvalid
     * @expectedException \CalendR\Period\Exception\NotAnHour
     * @return void
     */
    public function testConstructInvalidStrict($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(true);
        new Hour($start, $calendar->getFactory());
    }

    /**
     * Test: Invalid Constructors (Lazy)
     *
     * @access public
     * @param DateTime $start
     * @dataProvider providerConstructInvalid
     * @return void
     */
    public function testConstructInvalidLazy($start)
    {
        $calendar = new \CalendR\Calendar;
        $calendar->setStrictDates(false);
        new Hour($start, $calendar->getFactory());
    }

    /**
     * Test: Valid Constructors
     *
     * @access public
     * @param DateTime $start
     * @dataProvider providerConstructValid
     * @return void
     */
    public function testConstructValid($start)
    {
        new Hour($start);
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
     * Test: Contains
     *
     * @access public
     * @param DateTime $start
     * @param DateTime $contain
     * @param DateTime $notContain
     * @dataProvider providerContains
     * @return void
     */
    public function testContains($start, $contain, $notContain)
    {
        $hour = new Hour($start);
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
        $hour = new Hour(new \DateTime('2012-01-01 05:00'));
        $this->assertEquals('2012-01-01 06:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 14:00'));
        $this->assertEquals('2012-01-31 15:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2013-02-28 23:00'));
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
        $hour = new Hour(new \DateTime('2012-01-01 00:00'));
        $this->assertEquals('2011-12-31 23:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 05:00'));
        $this->assertEquals('2012-01-31 04:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
        $hour = new Hour(new \DateTime('2012-01-31 06:00'));
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
        $hour = new Hour(new \DateTime('2012-01-31 13:00'));
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
        $currentHour = new Hour(new \DateTime(date('Y-m-d H:00')));
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
        $hour = new Hour(new \DateTime(date('Y-m-d H:00')));
        $this->assertSame($hour->getBegin()->format('G'), (string) $hour);
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
     * Test: Includes
     *
     * @access public
     * @param DateTime $begin
     * @param PeriodInterface $period
     * @param boolean $strict
     * @param boolean $result
     * @dataProvider includesDataProvider
     * @return void
     */
    public function testIncludes(\DateTime $begin, PeriodInterface $period, $strict, $result)
    {
        $hour = new Hour($begin);
        $this->assertSame($result, $hour->includes($period, $strict));
    }

    /**
     * Test: Format
     *
     * @access public
     * @return void
     */
    public function testFormat()
    {
        $hour = new Hour(new \DateTime(date('Y-m-d H:00')));
        $this->assertSame(date('Y-m-d H:00'), $hour->format('Y-m-d H:i'));
    }

    /**
     * Test: Is Current?
     *
     * @access public
     * @return void
     */
    public function testIsCurrent()
    {
        $currentHour = new Hour(new \DateTime(date('Y-m-d H:00')));
        $otherHour   = new Hour(new \DateTime('1988-11-12 16:00'));
        $this->assertTrue($currentHour->isCurrent());
        $this->assertFalse($otherHour->isCurrent());
    }

    /**
     * Data Provider: Includes
     *
     * @static
     * @access public
     * @return array
     */
    public function includesDataProvider()
    {
        return array(
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Year(new \DateTime('2013-01-01')),            false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             true,   false),
            array(new \DateTime('2013-09-01 12:00'),  new Day(new \DateTime('2013-09-01')),             false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Hour(new \DateTime('2013-09-01 12:00')),      false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:34')),    true,   true),
            array(new \DateTime('2013-09-01 12:00'),  new Minute(new \DateTime('2013-09-01 12:34')),    false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:34:45')), false,  true),
            array(new \DateTime('2013-09-01 12:00'),  new Second(new \DateTime('2013-09-01 12:34:45')), false,  true),
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
        $hour = new Hour($start);
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
