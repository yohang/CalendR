<?php

namespace CalendR\Period;

/**
 * Represents a week.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Week extends PeriodAbstract implements \Iterator, \Stringable
{
    private ?PeriodInterface $current = null;

    public function getNumber(): int
    {
        return $this->begin->format('W');
    }

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        if ($start->format('H:i:s') !== '00:00:00') {
            return false;
        }

        return true;
    }

    public function current(): PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (!$this->valid()) {
            $this->current = $this->getFactory()->createDay($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    public function key(): string
    {
        return $this->current->getBegin()->format('d-m-Y');
    }

    public function valid(): bool
    {
        return null !== $this->current;
    }

    public function rewind(): void
    {
        $this->current = null;
        $this->next();
    }

    public function __toString(): string
    {
        return $this->format('W');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1W');
    }
}
