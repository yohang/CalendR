<?php

namespace CalendR\Period;

use CalendR\Calendar;

/**
 * Represents a week
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Week extends PeriodAbstract implements \Iterator
{
    private $current = null;

    /**
     * @param \DateTime $start
     * @throw Exception\NotAWeek
     */
    public function __construct(\DateTime $start)
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAWeek;
        }

        $this->begin = clone $start;
        $this->end = clone $start;
        $this->end->add(new \DateInterval('P7D'));
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function contains(\DateTime $date)
    {
        return
            $this->begin->diff($date)->invert == 0 &&
            $this->end->diff($date)->invert == 1;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->begin->format('W');
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
     * @static
     * @param \DateTime $start
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        if (Calendar::getFirstWeekday() != $start->format('w')) {
            return false;
        }

        return true;
    }

    /*
    * Iterator implementation
    */
    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        if (!$this->valid()) {
            $this->current = new Day($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, integer
     * 0 on failure.
     */
    public function key()
    {
        return $this->current->getBegin()->format('d-m-Y');
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return null !== $this->current;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->current = null;
        $this->next();
    }

    /**
     * Returns the week number
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('W');
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @static
     * @return \DateInterval
     */
    static function getDateInterval()
    {
        return new \DateInterval('P1W');
    }
}
