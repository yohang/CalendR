<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Period\Exception\NotAnHour;

class Hour extends PeriodAbstract implements \IteratorAggregate, \Stringable
{
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1M'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00' === $start->format('i:s');
    }

    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createMinute($this->begin);
        while ($this->contains($current->getBegin())) {
            yield $current;

            $current = $current->getNext();
        }
    }

    public function __toString(): string
    {
        return $this->format('G');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1H');
    }

    protected function createInvalidException(): NotAnHour
    {
        return new NotAnHour();
    }
}
