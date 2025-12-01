<?php

declare(strict_types=1);

namespace CalendR\Event;

use CalendR\Period\PeriodInterface;

interface EventInterface
{
    public function getBegin(): \DateTimeInterface;

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

    public function isEqualTo(self $event): bool;
}
