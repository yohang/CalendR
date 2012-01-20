<?php

namespace CalendR\Period;

class Day implements PeriodInterface
{
    private $date;

    public function __construct(\DateTime $date)
    {
        $this->date = clone $date;
    }

    /**
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    public function contains(\DateTime $date)
    {
        return $this->date->format('d-m-Y') == $date->format('d-m-Y');
    }

    public static function isValid(\DateTime $start)
    {
        return true;
    }

    public function getDate()
    {
        return $this->date;
    }
}