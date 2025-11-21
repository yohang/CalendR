<?php

declare(strict_types=1);

namespace CalendR\Period;

class Day extends PeriodAbstract implements \Iterator, \Stringable
{
    private ?PeriodInterface $current = null;

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
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

    public function __toString(): string
    {
        return $this->format('l');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '00:00:00' === $start->format('H:i:s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1D');
    }
}
