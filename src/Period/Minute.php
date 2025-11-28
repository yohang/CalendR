<?php

declare(strict_types=1);

namespace CalendR\Period;

/**
 * @implements \IteratorAggregate<int, Second>
 * @implements IterablePeriod<int, Second>
 */
class Minute extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        $current = $this->getFactory()->createSecond($this->begin);
        while ($this->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('s') => $current;

            $current = $this->getFactory()->createSecond($current->getBegin()->modify('+1 second'));
        }
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format('i');
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00' === $start->format('s');
    }

    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1M');
    }
}
