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
 * Class FactoryInterface
 *
 * @package CalendR\Period
 */
interface FactoryInterface
{
    /**
     * Create and return a Day
     *
     * @param  \DateTime                       $begin
     * @return \CalendR\Period\PeriodInterface
     */
    public function createDay(\DateTime $begin);

    /**
     * Create and return a Week
     *
     * @param  \DateTime                       $begin
     * @return \CalendR\Period\PeriodInterface
     */
    public function createWeek(\DateTime $begin);

    /**
     * Create and return a Month
     *
     * @param  \DateTime                       $begin
     * @return \CalendR\Period\PeriodInterface
     */
    public function createMonth(\DateTime $begin);

    /**
     * Create and return a Year
     *
     * @param  \DateTime                       $begin
     * @return \CalendR\Period\PeriodInterface
     */
    public function createYear(\DateTime $begin);

    /**
     * Create and return a Range
     *
     * @param  \DateTime                       $begin
     * @param  \DateTime                       $end
     * @return \CalendR\Period\PeriodInterface
     */
    public function createRange(\DateTime $begin, \DateTime $end);

    /**
     * @param  integer $firstWeekday
     * @return null
     */
    public function setFirstWeekday($firstWeekday);

    /**
     * @return integer
     */
    public function getFirstWeekday();

    /**
     * Find the first day of the given week
     *
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function findFirstDayOfWeek($dateTime);
}
