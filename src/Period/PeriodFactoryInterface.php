<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\DayOfWeek;

interface PeriodFactoryInterface
{
    public function createSecond(\DateTimeInterface $begin): Second;

    public function createMinute(\DateTimeInterface $begin): Minute;

    public function createHour(\DateTimeInterface $begin): Hour;

    public function createDay(\DateTimeInterface $begin): Day;

    public function createWeek(\DateTimeInterface $begin): Week;

    public function createMonth(\DateTimeInterface $begin): Month;

    public function createYear(\DateTimeInterface $begin): Year;

    public function createRange(\DateTimeInterface $begin, \DateTimeInterface $end): Range;

    public function setFirstWeekday(DayOfWeek $firstWeekday): void;

    public function getFirstWeekday(): DayOfWeek;

    public function findFirstDayOfWeek(\DateTimeInterface $dateTime): \DateTimeImmutable;
}
