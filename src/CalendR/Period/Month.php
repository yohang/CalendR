<?php

namespace CalendR\Period;

/**
 * A calendar Month
 */
class Month implements \Iterator, PeriodInterface
{
    /**
     * @var \DatePeriod
     */
    private $period;

    /**
     * @var Week
     */
    private $current;

    /**
     * @var \DateTime
     */
    private $begin;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @param \DateTime $start
     */
    public function __construct(\DateTime $start)
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAMonth;
        }

        $this->begin = clone $start;
        $this->end = clone $this->begin;
        $this->end->add(new \DateInterval('P1M'));
        $this->period = new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function contains(\DateTime $date)
    {
        return $date->format('m') == $this->begin->format('m');
    }

    public static function isValid(\DateTime $start)
    {
        if (1 != $start->format('d')) {
            return false;
        }

        return true;
    }

    /**
     * @return Month
     */
    public function getNext()
    {
        return new self($this->end);
    }

    /**
     * @return Month
     */
    public function getPrevious()
    {
        $start = clone $this->begin;
        $start->sub(new \DateInterval('P1M'));

        return new self($start);
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

    /*
    * Iterator implementation
    */

    /**
     * @return Week
     */
    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        if (!$this->valid()) {
            $delta = $this->begin->format('w');
            $delta = $delta ?: 7;
            $delta--;

            $start = clone $this->begin;

            $this->current = new Week($start->sub(new \DateInterval(sprintf('P%sD', $delta))));
        } else {
            $start = clone $this->current->getEnd();

            if ($start->format('m') == $this->begin->format('m')) {
                $end = clone $start;
                $this->current = new Week($start, $end->add(new \DateInterval('P7D')));
            } else {
                $this->current = null;
            }
        }
    }

    public function key()
    {
        return $this->current->getNumber();
    }

    public function valid()
    {
        return null !== $this->current();
    }

    public function rewind()
    {
        $this->current = null;
        $this->next();
    }


}
