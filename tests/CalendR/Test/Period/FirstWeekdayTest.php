<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Period\Range;
use CalendR\Period\Week;
use CalendR\Period\Year;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class FirstWeekdayTest extends \PHPUnit_Framework_TestCase
{
    public function testIterateOnMonth()
    {
        $calendar = new Calendar;
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(Day::MONDAY, (int) $week->getBegin()->format('w'));
        }

        $calendar->setFirstWeekday(Day::SUNDAY);
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(Day::SUNDAY, (int) $week->getBegin()->format('w'));
        }
    }

    public function testSetFirstWeekday()
    {
        $factory = new Calendar;

        $factory->setFirstWeekday(Day::FRIDAY);
        $this->assertSame(Day::FRIDAY, $factory->getFirstWeekday());

        $factory->setFirstWeekday(Day::THURSDAY);
        $this->assertSame(Day::THURSDAY, $factory->getFirstWeekday());
    }
}
