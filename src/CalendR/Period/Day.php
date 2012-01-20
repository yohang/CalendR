<?php

namespace CalendR\Period;

class Day implements PeriodInterface
{
    /**
     * @var \DateTime
     */
    private $begin;

    /**
     * @var \DateTime
     */
    private $end;

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
     * @return Day
     */
    public function getNext()
    {
        return new self($this->end);
    }

    /**
     * @return Day
     */
    public function getPrevious()
    {
        $previous = clone $this;
        $previous->begin->sub(new \DateInterval('P1D'));

        return $previous;
    }

    /**
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }


}