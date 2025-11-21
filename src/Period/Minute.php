<?php

namespace CalendR\Period;

/**
 * Represents a minute.
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Minute extends PeriodAbstract implements \Iterator, \Stringable
{
    private ?PeriodInterface $current = null;

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00' === $start->format('s');
    }

    public function current(): ?PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (!$this->current instanceof PeriodInterface) {
            $this->current = $this->getFactory()->createSecond($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    public function key(): int
    {
        return (int) $this->current->getBegin()->format('i');
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

    public function __toString(): string
    {
        return $this->format('i');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('PT1M');
    }
}
