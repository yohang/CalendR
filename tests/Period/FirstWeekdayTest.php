<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\DayOfWeek;
use CalendR\Period\Day;
use PHPUnit\Framework\TestCase;

final class FirstWeekdayTest extends TestCase
{
    public function testIterateOnMonth(): void
    {
        $calendar = new Calendar();
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(DayOfWeek::MONDAY->value, (int) $week->getBegin()->format('w'));
        }

        $calendar->setFirstWeekday(DayOfWeek::SUNDAY);
        $month = $calendar->getMonth(2013, 3);

        foreach ($month as $week) {
            $this->assertSame(DayOfWeek::SUNDAY->value, (int) $week->getBegin()->format('w'));
        }
    }

    public function testSetFirstWeekday(): void
    {
        $factory = new Calendar();

        $factory->setFirstWeekday(DayOfWeek::FRIDAY);
        $this->assertSame(DayOfWeek::FRIDAY, $factory->getFirstWeekday());

        $factory->setFirstWeekday(DayOfWeek::THURSDAY);
        $this->assertSame(DayOfWeek::THURSDAY, $factory->getFirstWeekday());
    }
}
