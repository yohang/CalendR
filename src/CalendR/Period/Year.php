<?php

namespace CalendR\Period;

/**
 * Represents a year
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Year extends PeriodAbstract implements \Iterator
{
    /**
     * @var Month
     */
    private $current;

    /**
     * @param \DateTime $begin
     * @param int       $firstWeekday
     *
     * @throws Exception\NotAYear
     */
    public function __construct(\DateTime $begin, $firstWeekday = Day::MONDAY)
    {
        if (!self::isValid($begin)) {
            throw new Exception\NotAYear;
        }

        $this->begin = clone $begin;
        $this->end = clone $begin;
        $this->end->add(new \DateInterval('P1Y'));

        parent::__construct($firstWeekday);
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
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('d-m') == '01-01';
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        if (null === $this->current) {
            $this->current = new Month($this->begin, $this->firstWeekday);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->current->getBegin()->format('m');
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return null !== $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->current = null;
        $this->next();
    }

    /**
     * Returns the year
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('Y');
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('P1Y');
    }
}
