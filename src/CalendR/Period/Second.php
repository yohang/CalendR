<?php

namespace CalendR\Period;

/**
 * Represents a second.
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 */
class Second extends PeriodAbstract
{
    /**
     * Returns the period as a DatePeriod.
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('u') === '000000';
    }

    /**
     * Returns the second.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('s');
    }

    /**
     * Returns a \DateInterval equivalent to the period.
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('PT1S');
    }
}
