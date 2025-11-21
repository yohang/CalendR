<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\DayOfWeek;

final class Factory implements FactoryInterface
{
    public function __construct(
        private DayOfWeek $firstWeekday = DayOfWeek::MONDAY,
    ) {
    }

    public function createSecond(\DateTimeInterface $begin): Second
    {
        return new Second($begin, $this);
    }

    public function createMinute(\DateTimeInterface $begin): Minute
    {
        return new Minute($begin, $this);
    }

    public function createHour(\DateTimeInterface $begin): Hour
    {
        return new Hour($begin, $this);
    }

    public function createDay(\DateTimeInterface $begin): Day
    {
        return new Day($begin, $this);
    }

    public function createWeek(\DateTimeInterface $begin): Week
    {
        return new Week($begin, $this);
    }

    public function createMonth(\DateTimeInterface $begin): Month
    {
        return new Month($begin, $this);
    }

    public function createYear(\DateTimeInterface $begin): Year
    {
        return new Year($begin, $this);
    }

    public function createRange(\DateTimeInterface $begin, \DateTimeInterface $end): Range
    {
        return new Range($begin, $end, $this);
    }

    public function setFirstWeekday(DayOfWeek $firstWeekday): void
    {
        $this->firstWeekday = $firstWeekday;
    }

    public function getFirstWeekday(): DayOfWeek
    {
        return $this->firstWeekday;
    }

    public function findFirstDayOfWeek(\DateTimeInterface $dateTime): \DateTimeInterface
    {
        $day = clone $dateTime;
        $delta = ((int) $day->format('w') - $this->getFirstWeekday()->value + 7) % 7;

        return $day->sub(new \DateInterval(\sprintf('P%sD', $delta)));
    }
}
