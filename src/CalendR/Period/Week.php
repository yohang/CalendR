<?php

namespace CalendR\Period;

class Week implements \IteratorAggregate, PeriodInterface
{
    /**
     * @var \DatePeriod
     */
    private $period;

    /**
     * @var int
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    public function __construct(\DateTime $start, \DateTime $end)
    {
        $this->start = clone $start;
        $this->end = clone $end;

        $this->period = new \DatePeriod($this->start, new \DateInterval('P1D'), $this->end);
        $this->number = $start->format('W');
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function contains(\DateTime $date)
    {
        return $date->format('W') == $this->number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getIterator()
    {
        return $this->period;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

}
