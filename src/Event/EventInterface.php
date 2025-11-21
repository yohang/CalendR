<?php

declare(strict_types=1);

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
 * Base interface for events implementation.
 *
 * @author Yohan Giarelli <yohan@giare.li>
 */
interface EventInterface
{
    /**
     * Returns an unique identifier for the Event.
     * Could be any string, but MUST to be unique.
     *   ex : 'event-8', 'meeting-43'.
     *
     * @abstract
     *
     * @return string an unique event identifier
     */
    public function getUid(): string;

    /**
     * Returns the event begin.
     */
    public function getBegin(): \DateTimeInterface;

    /**
     * Returns the event end.
     */
    public function getEnd(): \DateTimeInterface;

    /**
     * Check if the given date is during the event.
     */
    public function contains(\DateTimeInterface $datetime): bool;

    /**
     * Check if the given period is during the event.
     */
    public function containsPeriod(PeriodInterface $period): bool;

    /**
     * Check if the event is during the given period.
     */
    public function isDuring(PeriodInterface $period): bool;
}
