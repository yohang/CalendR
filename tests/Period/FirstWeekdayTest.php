<?php

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Day;
use PHPUnit\Framework\TestCase;

/**
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class FirstWeekdayTest extends TestCase
{
    public function testIterateOnMonth(): void
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

    public function testSetFirstWeekday(): void
    {
        $factory = new Calendar;

        $factory->setFirstWeekday(Day::FRIDAY);
        $this->assertSame(Day::FRIDAY, $factory->getFirstWeekday());

        $factory->setFirstWeekday(Day::THURSDAY);
        $this->assertSame(Day::THURSDAY, $factory->getFirstWeekday());
    }
}
