<?php

declare(strict_types=1);

namespace CalendR\Period;

/**
 * @implements \IteratorAggregate<string, Day>
 * @implements IterablePeriod<string, Day>
 */
final class Week extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
    public function getNumber(): int
    {
        return (int) $this->begin->format('W');
    }

    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    #[\Override]
    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createDay($this->begin);
        while ($this->contains($current->getBegin())) {
            yield $current->getBegin()->format('d-m-Y') => $current;

            $current = $current->getNext();
        }
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format('W');
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1W');
    }
}
