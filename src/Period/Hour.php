<?php

namespace CalendR\Period;

use CalendR\Period\Exception\NotAnHour;

/**
 * Represents an hour.
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Hour extends PeriodAbstract implements \Iterator, \Stringable
{
    private ?PeriodInterface $current = null;

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1M'), $this->end);
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return $start->format('i:s') === '00:00';
    }

    public function current(): ?PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (!$this->current instanceof PeriodInterface) {
            $this->current = $this->getFactory()->createMinute($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    public function key(): int
    {
        return (int) $this->current->getBegin()->format('G');
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
