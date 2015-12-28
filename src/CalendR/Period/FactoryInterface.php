<?php
/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

/**
 * Class FactoryInterface.
 */
interface FactoryInterface
{
    /**
     * Create and return a Second.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createSecond(\DateTime $begin);

    /**
     * Create and return a Minute.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createMinute(\DateTime $begin);

    /**
     * Create and return an Hour.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createHour(\DateTime $begin);

    /**
     * Create and return a Day.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createDay(\DateTime $begin);

    /**
     * Create and return a Week.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createWeek(\DateTime $begin);

    /**
     * Create and return a Month.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createMonth(\DateTime $begin);

    /**
     * Create and return a Year.
     *
     * @param \DateTime $begin
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createYear(\DateTime $begin);

    /**
     * Create and return a Range.
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     *
     * @return \CalendR\Period\PeriodInterface
     */
    public function createRange(\DateTime $begin, \DateTime $end);

    /**
     * @param int $firstWeekday
     */
    public function setFirstWeekday($firstWeekday);

    /**
     * @return int
     */
    public function getFirstWeekday();

    /**
     * Find the first day of the given week.
     *
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function findFirstDayOfWeek($dateTime);
}
