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

use CalendR\Event\EventInterface;

/**
 * Interface that all Periods must implement.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
interface PeriodInterface
{
    /**
     * Checks if the given period is contained in the current period
     *
     * @param \DateTime $date
     *
     * @return bool true if the period contains this date
     */
    public function contains(\DateTime $date);

    /**
     * Gets the DateTime of period begin
     *
     * @return \DateTime
     */
    public function getBegin();

    /**
     * Gets the DateTime of the period end
     *
     * @return \DateTime
     */
    public function getEnd();

    /**
     * Gets the next period of the same type
     *
     * @return PeriodInterface
     */
    public function getNext();

    /**
     * Gets the previous period of the same type
     *
     * @return PeriodInterface
     */
    public function getPrevious();

    /**
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod();

    /**
     * Checks if a period is equals to an other
     *
     * @param PeriodInterface $period
     *
     * @return bool
     */
    public function equals(PeriodInterface $period);

    /**
     * Returns true if the period include the other period
     * given as argument
     *
     * @param PeriodInterface $period
     * @param bool            $strict
     */
    public function includes(PeriodInterface $period, $strict = true);

    /**
     * Returns if $event is during this period.
     * Non strict. Must return true if :
     *  * Event is during period
     *  * Period is during event
     *  * Event begin is during Period
     *  * Event end is during Period
     *
     * @param EventInterface $event
     *
     * @return boolean
     */
    public function containsEvent(EventInterface $event);

    /**
     * Format the period to a string
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format);

    /**
     * Returns if the current period is the current one
     *
     * @return boolean
     */
    public function isCurrent();

    /**
     * Sets the first day of week
     *
     * @param int $firstWeekday
     */
    public function setFirstWeekday($firstWeekday);

    /**
     * Checks if $start is good for building the period
     *
     * @param \DateTime $start
     */
    public static function isValid(\DateTime $start);

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @return \DateInterval
     */
    public static function getDateInterval();
}
