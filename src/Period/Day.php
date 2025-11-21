<?php

declare(strict_types=1);

namespace CalendR\Period;

class Day extends PeriodAbstract implements \IteratorAggregate, \Stringable
{
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createHour($this->begin);
        while ($this->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('G') => $current;

            $current = $current->getNext();
        }
    }

    public function __toString(): string
    {
        return $this->format('l');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1D');
    }
}
