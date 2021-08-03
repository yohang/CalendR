<?php

/*
 * This file has been added to CalendR, a Fréquence web project.
 *
 * (c) 2012 Ingewikkeld/Stefan Koopmanschap
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

/**
 * Represents a Range.
 *
 * @author Stefan Koopmanschap <left@leftontheweb.com>
 */
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
        throw new Exception\NotImplemented('Range period doesn\'t support getDateInterval().');
    }
}
