<?php

namespace CalendR\Period;

/**
 * Represents a Month
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Month extends PeriodAbstract implements \Iterator
{
    /**
     * @var Week
     */
    private $current;

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
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function contains(\DateTime $date)
    {
        return $date->format('Y-m') == $this->begin->format('Y-m');
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
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * Returns a Day array
     *
     * @return array|Day
     */
    public function getDays()
    {
        $days = array();
        foreach ($this->getDatePeriod() as $date) {
            $days[] = new Day($date);
        }

        return $days;
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
            $this->current = $this->current->getNext();

            if ($this->current->getBegin()->format('m') != $this->begin->format('m')) {
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
