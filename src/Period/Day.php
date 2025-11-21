<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

/**
 * Represents a Day.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Day extends PeriodAbstract implements \Iterator, \Stringable
{
    public const MONDAY    = 1;
    public const TUESDAY   = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY  = 4;
    public const FRIDAY    = 5;
    public const SATURDAY  = 6;
    public const SUNDAY    = 0;

    private ?PeriodInterface $current = null;

    /**
     * Returns the period as a DatePeriod.
     */
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * Returns the day name (probably in english).
     */
    public function __toString(): string
    {
        return $this->format('l');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    /**
     * Returns a \DateInterval equivalent to the period.
     */
    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1D');
    }

    public function current(): ?PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (!$this->current instanceof PeriodInterface) {
            $this->current = $this->getFactory()->createHour($this->begin);
        } else {
            $this->current = $this->current->getNext();

            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    public function key(): int
    {
        return (int)$this->current->getBegin()->format('G');
    }

    public function valid(): bool
    {
        return $this->current instanceof PeriodInterface;
    }

    public function rewind(): void
    {
        $this->current = null;

        $this->next();
    }
}
