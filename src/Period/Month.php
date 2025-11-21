<?php

namespace CalendR\Period;

/**
 * Represents a Month.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Month extends PeriodAbstract implements \Iterator, \Stringable
{
    private ?PeriodInterface $current = null;

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * Returns a Day array.
     *
     * @return array<Day>
     */
    public function getDays(): array
    {
        $days = [];
        foreach ($this->getDatePeriod() as $date) {
            $days[] = $this->getFactory()->createDay($date);
        }

        return $days;
    }

    /**
     * Returns the first day of the first week of month.
     * First day of week is configurable via {@link Factory:setOption()}.
     */
    public function getFirstDayOfFirstWeek(): \DateTimeInterface
    {
        return $this->getFactory()->findFirstDayOfWeek($this->begin);
    }

    /**
     * Returns a Range period beginning at the first day of first week of this month,
     * and ending at the last day of the last week of this month.
     */
    public function getExtendedMonth(): PeriodInterface
    {
        return $this->getFactory()->createRange($this->getFirstDayOfFirstWeek(), $this->getLastDayOfLastWeek());
    }

    /**
     * Returns the last day of last week of month
     * First day of week is configurable via {@link Factory::setOption()}.
     */
    public function getLastDayOfLastWeek(): \DateTimeInterface
    {
        $lastDay = (clone $this->end)->sub(new \DateInterval('P1D'));

        return $this->getFactory()->findFirstDayOfWeek($lastDay)->add(new \DateInterval('P6D'));
    }

    public function current(): ?PeriodInterface
    {
        return $this->current;
    }

    public function next(): void
    {
        if (!$this->valid()) {
            $this->current = $this->getFactory()->createWeek($this->getFirstDayOfFirstWeek());
        } else {
            $this->current = $this->current->getNext();

            if ($this->current->getBegin()->format('m') !== $this->begin->format('m')) {
                $this->current = null;
            }
        }
    }

    public function key(): int
    {
        return $this->current->getBegin()->format('W');
    }

    public function valid(): bool
    {
        return null !== $this->current();
    }

    public function rewind(): void
    {
        $this->current = null;
        $this->next();
    }

    /**
     * Returns the month name (probably in english).
     */
    public function __toString(): string
    {
        return $this->format('F');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return $start->format('d H:i:s') === '01 00:00:00';
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1M');
    }
}
