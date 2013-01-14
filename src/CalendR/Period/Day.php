<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

/**
 * Represents a Day
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Day extends PeriodAbstract
{
    const Sunday = 0;
    const Monday = 1;
    const Tuesday = 2;
    const Wednesday = 3;
    const Thursday = 4;
    const Friday = 5;
    const Saturday = 6;

    public function __construct(\DateTime $begin)
    {
        $this->begin = clone $begin;
        $this->end = clone $begin;
        $this->end->add(new \DateInterval('P1D'));
    }

    /**
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    public function contains(\DateTime $date)
    {
        return $this->begin->format('d-m-Y') == $date->format('d-m-Y');
    }

    public static function isValid(\DateTime $start)
    {
        return true;
    }

    /**
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * Returns the day name (probably in english)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('l');
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @static
     * @return \DateInterval
     */
    static function getDateInterval()
    {
        return new \DateInterval('P1D');
    }
}
