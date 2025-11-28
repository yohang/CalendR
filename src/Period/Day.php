<?php

declare(strict_types=1);

namespace CalendR\Period;

/**
 * @implements \IteratorAggregate<int, Hour>
 * @implements IterablePeriod<int, Hour>
 */
class Day extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    #[\Override]
    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createHour($this->begin);
        while ($this->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('G') => $current;

            $current = $current->getNext();
        }
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format('l');
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1D');
    }
}
