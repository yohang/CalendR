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
     * @param \DateTime        $begin
     * @param FactoryInterface $factory
     *
     * @throws Exception\NotASecond
     */
    public function __construct(\DateTime $begin, $factory = null)
    {
        parent::__construct($factory);
        if ($this->getFactory()->getStrictDates() && !self::isValid($begin)) {
            throw new Exception\NotASecond();
        }

        if (!self::isValid($begin)) {
            @trigger_error('The non-strict construction of time periods is deprecated and will be removed in 2.0. build your period using the Calendar class.', E_USER_DEPRECATED);
        }

        // Not in strict mode, accept any timestamp and set the begin date back to the beginning of this period.
        $this->begin = clone $begin;
        // Still do this to make sure there aren't any microseconds.
        $this->begin->setTime($this->begin->format('G'), $this->begin->format('i'), $this->begin->format('s'));

        $this->end = clone $begin;
        $this->end->add($this->getDateInterval());
    }

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
