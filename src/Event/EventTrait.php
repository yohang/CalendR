<?php

declare(strict_types=1);

namespace CalendR\Event;

use CalendR\Period\PeriodInterface;

trait EventTrait
{
    abstract public function getBegin(): \DateTimeInterface;

    abstract public function getEnd(): \DateTimeInterface;

    public function contains(\DateTimeInterface $datetime): bool
    {
        return $this->getBegin() <= $datetime && $datetime < $this->getEnd();
    }

    public function containsPeriod(PeriodInterface $period): bool
    {
        return $this->getBegin() <= $period->getBegin() && $this->getEnd() >= $period->getEnd();
    }

    public function isDuring(PeriodInterface $period): bool
    {
        return $this->getBegin() >= $period->getBegin() && $this->getEnd() < $period->getEnd();
    }
}
