<?php

namespace CalendR\Period;

/**
 * Interface for Period factories.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
interface FactoryInterface
{
    /**
     * Create and return a Second.
     */
    public function createSecond(\DateTimeInterface $begin): Second;

    /**
     * Create and return a Minute.
     */
    public function createMinute(\DateTimeInterface $begin): Minute;

    /**
     * Create and return an Hour.
     */
    public function createHour(\DateTimeInterface $begin): Hour;

    /**
     * Create and return a Day.
     */
    public function createDay(\DateTimeInterface $begin): Day;

    /**
     * Create and return a Week.
     */
    public function createWeek(\DateTimeInterface $begin): Week;

    /**
     * Create and return a Month.
     */
    public function createMonth(\DateTimeInterface $begin): Month;

    /**
     * Create and return a Year.
     */
    public function createYear(\DateTimeInterface $begin): Year;

    /**
     * Create and return a Range.
     */
    public function createRange(\DateTimeInterface $begin, \DateTimeInterface $end): Range;

    /**
     * Define the first day of week (e.g. Monday in France and Sunday in U.S.)
     */
    public function setFirstWeekday(int $firstWeekday): void;

    /**
     * Returns the first day of week (e.g. Monday in France and Sunday in U.S.)
     */
    public function getFirstWeekday(): int;

    /**
     * Find the first day of the given week.
     */
    public function findFirstDayOfWeek(\DateTimeInterface $dateTime): \DateTimeInterface;
}
