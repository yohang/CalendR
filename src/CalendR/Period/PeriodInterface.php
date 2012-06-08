<?php

namespace CalendR\Period;

interface PeriodInterface
{
    /**
     * Checks if the given period is contained in the current period
     *
     * @abstract
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    function contains(\DateTime $date);

    /**
     * Gets the DateTime of period begin
     *
     * @abstract
     * @return \DateTime
     */
    function getBegin();

    /**
     * Gets the DateTime of the period end
     *
     * @abstract
     * @return \DateTime
     */
    function getEnd();

    /**
     * Gets the next period of the same type
     *
     * @abstract
     * @return PeriodInterface
     */
    function getNext();

    /**
     * Gets the previous period of the same type
     *
     * @abstract
     * @return PeriodInterface
     */
    function getPrevious();

    /**
     * Returns the period as a DatePeriod
     *
     * @abstract
     * @return \DatePeriod
     */
    function getDatePeriod();

    /**
     * Checks if a period is equals to an other
     *
     * @abstract
     * @param PeriodInterface $period
     * @return boolean
     */
    function equals(PeriodInterface $period);

    /**
     * Returns true if the period include the other period
     * given as argument
     *
     * @abstract
     * @param PeriodInterface $period
     * @param bool $strict
     */
    function includes(PeriodInterface $period, $strict = true);

    /**
     * Checks if $start is good for building the period
     *
     * @static
     * @abstract
     * @param \DateTime $start
     */
    static function isValid(\DateTime $start);
}
