<?php

namespace CalendR\Period;

/**
 * Represents a year.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Year extends PeriodAbstract implements \Iterator
{
    private ?PeriodInterface $current = null;

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return $start->format('d-m H:i:s') === '01-01 00:00:00';
    }

    public function current(): PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (null === $this->current) {
            $this->current = $this->getFactory()->createMonth($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    public function key(): int
    {
        return $this->current->getBegin()->format('m');
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
        return $this->format('Y');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1Y');
    }
}
