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
        // keeping microsecond check for backwards compatability with PHP < 7.1
        // otherwise, any DateTime is valid, even with microseconds
        return PHP_VERSION_ID < 70100 ? $start->format('u') === '000000' : true;
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
