<?php

declare(strict_types=1);

namespace CalendR\Period;

final class Second extends PeriodAbstract implements \Stringable
{
    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return '000000' === $start->format('u');
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format('s');
    }

    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1S');
    }
}
