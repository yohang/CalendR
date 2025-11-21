<?php

declare(strict_types=1);

namespace CalendR\Period;

class Minute extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    public function getIterator(): \Traversable
    {
        $current = $this->factory->createSecond($this->begin);
        while ($this->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('s') => $current;

            $current = $this->factory->createSecond($current->getBegin()->modify('+1 second'));
        }
    }

    public function __toString(): string
    {
        return $this->format('i');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00' === $start->format('s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1M');
    }
}
