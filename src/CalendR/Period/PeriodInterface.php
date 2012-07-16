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
     * @abstract
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    function contains(\DateTime $date);

    /**
     * Gets the DateTime of period begin
     *
     * @abstract
     * @return \DateTime
     */
    function getBegin();

    /**
     * Gets the DateTime of the period end
     *
     * @abstract
     * @return \DateTime
     */
    function getEnd();

    /**
     * Gets the next period of the same type
     *
     * @abstract
     * @return PeriodInterface
     */
    function getNext();

    /**
     * Gets the previous period of the same type
     *
     * @abstract
     * @return PeriodInterface
     */
    function getPrevious();

    /**
     * Returns the period as a DatePeriod
     *
     * @abstract
     * @return \DatePeriod
     */
    function getDatePeriod();

    /**
     * Checks if a period is equals to an other
     *
     * @abstract
     * @param PeriodInterface $period
     * @return boolean
     */
    function equals(PeriodInterface $period);

    /**
     * Returns true if the period include the other period
     * given as argument
     *
     * @abstract
     * @param PeriodInterface $period
     * @param bool $strict
     */
    function includes(PeriodInterface $period, $strict = true);

    /**
     * Returns if $event is during this period.
     * Non strict. Must return true if :
     *  * Event is during period
     *  * Period is during event
     *  * Event begin is during Period
     *  * Event end is during Period
     *
     * @abstract
     * @param EventInterface $event
     * @return boolean
     */
    function containsEvent(EventInterface $event);

    /**
     * Checks if $start is good for building the period
     *
     * @static
     * @abstract
     * @param \DateTime $start
     */
    static function isValid(\DateTime $start);
}
