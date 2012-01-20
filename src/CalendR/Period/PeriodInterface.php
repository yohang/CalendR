<?php

namespace CalendR\Period;

interface PeriodInterface
{
    /**
     * @abstract
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    function contains(\DateTime $date);

    /**
     * @abstract
     * @return \DateTime
     */
    function getBegin();

    /**
     * @abstract
     * @return \DateTime
     */
    function getEnd();

    /**
     * @abstract
     * @return PeriodInterface
     */
    function getNext();

    /**
     * @abstract
     * @return PeriodInterface
     */
    function getPrevious();

    static function isValid(\DateTime $start);
}
