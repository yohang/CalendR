<?php

declare(strict_types=1);

namespace CalendR\Period;

class Year extends PeriodAbstract implements \IteratorAggregate, \Stringable
{
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '01-01 00:00:00' === $start->format('d-m H:i:s');
    }

    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createMonth($this->begin);
        while ($this->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('m') => $current;

            $current = $current->getNext();
        }
    }

    public function __toString(): string
    {
        return $this->format('Y');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1Y');
    }
}
