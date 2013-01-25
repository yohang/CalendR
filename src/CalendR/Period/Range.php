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
 * Represents a Range
 *
 * @author Stefan Koopmanschap <left@leftontheweb.com>
 */
class Range extends PeriodAbstract
{
    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param int       $firstWeekday
     */
    public function __construct(\DateTime $begin, \DateTime $end, $firstWeekday = Day::MONDAY)
    {
        $this->begin = clone $begin;
        $this->end   = clone $end;

        parent::__construct($firstWeekday);
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return true;
    }

    /**
     * @return Day
     */
    public function getNext()
    {
        $diff = $this->begin->diff($this->end);
        $begin = clone($this->begin);
        $begin->add($diff);
        $end = clone($this->end);
        $end->add($diff);

        return new self($begin, $end, $this->firstWeekday);
    }

    /**
     * @return Day
     */
    public function getPrevious()
    {
        $diff = $this->begin->diff($this->end);
        $begin = clone($this->begin);
        $begin->sub($diff);
        $end = clone($this->end);
        $end->sub($diff);

        return new self($begin, $end, $this->firstWeekday);
    }

    /**
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, $this->begin->diff($this->end), $this->end);
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @throws Exception\NotImplemented
     */
    public static function getDateInterval()
    {
      throw new Exception\NotImplemented('Range period doesn\'t support getDateInterval().');
    }
}
