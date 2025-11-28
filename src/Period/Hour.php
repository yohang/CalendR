<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Period\Exception\NotAnHour;

/**
 * @implements \IteratorAggregate<int, Minute>
 * @implements IterablePeriod<int, Minute>
 */
final class Hour extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1M'), $this->end);
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00' === $start->format('i:s');
    }

    #[\Override]
    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createMinute($this->begin);
        while ($this->contains($current->getBegin())) {
            yield $current;

            $current = $current->getNext();
        }
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format('G');
    }

    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1H');
    }

    #[\Override]
    protected function createInvalidException(): NotAnHour
    {
        return new NotAnHour();
    }
}
