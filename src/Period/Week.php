<?php

declare(strict_types=1);

namespace CalendR\Period;

class Week extends PeriodAbstract implements \IteratorAggregate, \Stringable
{
    public function getNumber(): int
    {
        return (int) $this->begin->format('W');
    }

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public function getIterator(): \Generator
    {
        $current = $this->factory->createDay($this->begin);
        while ($this->contains($current->getBegin())) {
            yield $current->getBegin()->format('d-m-Y') => $current;

            $current = $current->getNext();
        }
    }

    public function __toString(): string
    {
        return $this->format('W');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1W');
    }
}
