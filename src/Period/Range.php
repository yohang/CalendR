<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Period\Exception\NotImplemented;

class Range extends PeriodAbstract
{
    public function __construct(\DateTimeInterface $begin, \DateTimeInterface $end, ?FactoryInterface $factory = null)
    {
        $this->factory = $factory;
        $this->begin   = clone $begin;
        $this->end     = clone $end;
    }

    public static function isValid(\DateTimeInterface $start): bool
    {
        return true;
    }

    public function getNext(): Range
    {
        $diff  = $this->begin->diff($this->end);
        $begin = (clone $this->begin)->add($diff);
        $end   = (clone $this->end)->add($diff);

        return new self($begin, $end, $this->factory);
    }

    public function getPrevious(): Range
    {
        $diff  = $this->begin->diff($this->end);
        $begin = (clone $this->begin)->sub($diff);
        $end   = (clone $this->end)->sub($diff);

        return new self($begin, $end, $this->factory);
    }

    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, $this->begin->diff($this->end), $this->end);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception\NotImplemented
     */
    public static function getDateInterval(): \DateInterval
    {
        throw new NotImplemented('Range period doesn\'t support getDateInterval().');
    }
}
