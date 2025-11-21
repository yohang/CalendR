<?php

declare(strict_types=1);

namespace CalendR\Period;

class Month extends PeriodAbstract implements \IteratorAggregate, \Stringable, IterablePeriod
{
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
     * First day of week is configurable via {@link Factory}.
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
     * First day of week is configurable via {@link Factory}.
     */
    public function getLastDayOfLastWeek(): \DateTimeInterface
    {
        $lastDay = (clone $this->end)->sub(new \DateInterval('P1D'));

        return $this->getFactory()->findFirstDayOfWeek($lastDay)->add(new \DateInterval('P6D'));
    }

    public function getIterator(): \Generator
    {
        $current = $this->getFactory()->createWeek($this->getFirstDayOfFirstWeek());
        while ($this->getExtendedMonth()->contains($current->getBegin())) {
            yield (int) $current->getBegin()->format('W') => $current;

            $current = $current->getNext();
        }
    }

    public function __toString(): string
    {
        return $this->format('F');
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return '01 00:00:00' === $start->format('d H:i:s');
    }

    public static function getDateInterval(): \DateInterval
    {
        return new \DateInterval('P1M');
    }
}
