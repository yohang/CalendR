<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event;

use CalendR\Period\PeriodInterface;

/**
 * Base interface for events implementation
 *
 * @author Yohan Giarelli <yohan@giare.li>
 */
interface EventInterface
{
    /**
     * Returns an unique identifier for the Event.
     * Could be any string, but MUST to be unique.
     *   ex : 'event-8', 'meeting-43'
     *
     * @abstract
     * @return string an unique event identifier
     */
    public function getUid();

    /**
     * Returns the event begin
     *
     * @abstract
     * @return \DateTime event begin
     */
    public function getBegin();

    /**
     * Returns the event end
     *
     * @abstract
     * @return \DateTime event end
     */
    public function getEnd();

    /**
     * Check if the given date is during the event
     *
     * @abstract
     * @param  \DateTime $datetime
     * @return bool      true if $datetime is during the event, false otherwise
     */
    public function contains(\DateTime $datetime);

    /**
     * Check if the given period is during the event
     *
     * @abstract
     * @param  \CalendR\Period\PeriodInterface $period
     * @return bool                            true if $period is during the event, false otherwise
     */
    public function containsPeriod(PeriodInterface $period);

    /**
     * Check if the event is during the given period
     *
     * @abstract
     * @param  \CalendR\Period\PeriodInterface $period
     * @return bool                            true if the event is during $period, false otherwise
     */
    public function isDuring(PeriodInterface $period);
}
