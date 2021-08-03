<?php

namespace CalendR\Period;

/**
 * Represents a second.
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Second extends PeriodAbstract
{
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '000000' === $start->format('u');
    }

    public function __toString(): string
    {
        return $this->format('s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1S');
    }
}
