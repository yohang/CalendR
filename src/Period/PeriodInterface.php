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
     * Checks if the given period is contained in the current period.
     */
    public function contains(\DateTimeInterface $date): bool;

    /**
     * Gets the DateTime of period begin.
     */
    public function getBegin(): \DateTimeInterface;

    /**
     * Gets the DateTime of the period end.
     */
    public function getEnd(): \DateTimeInterface;

    /**
     * Gets the next period of the same type.
     */
    public function getNext(): PeriodInterface;

    /**
     * Gets the previous period of the same type.
     */
    public function getPrevious(): PeriodInterface;

    /**
     * Returns the period as a DatePeriod.
     */
    public function getDatePeriod(): \DatePeriod;

    /**
     * Checks if a period is equals to another.
     */
    public function equals(PeriodInterface $period): bool;

    /**
     * Returns true if the period include the other period
     * given as argument.
     */
    public function includes(PeriodInterface $period, bool $strict = true): bool;

    /**
     * Returns if $event is during this period.
     * Non-strict. Must return true if :
     *  * Event is during period
     *  * Period is during event
     *  * Event begin is during Period
     *  * Event end is during Period.
     */
    public function containsEvent(EventInterface $event): bool;

    /**
     * Format the period to a string.
     */
    public function format(string $format): string;

    /**
     * Returns if the current period is the current one.
     */
    public function isCurrent(): bool;

    /**
     * Checks if $start is good for building the period.
     */
    public static function isValid(\DateTimeInterface $start): bool;

    /**
     * Returns a \DateInterval equivalent to the period.
     */
    public static function getDateInterval(): \DateInterval;
}
