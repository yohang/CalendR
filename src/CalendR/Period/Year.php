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

    public function __construct(\DateTime $begin)
    {
        if (!self::isValid($begin)) {
            throw new Exception\NotAYear;
        }

        $this->begin = clone $begin;
        $this->end = clone $begin;
        $this->end->add(new \DateInterval('P1Y'));
    }

    /**
     * @param \DateTime $date
     * @return true if the period contains this date
     */
    public function contains(\DateTime $date)
    {
        return $date->format('Y') == $this->begin->format('Y');
    }

    /**
     * @return PeriodInterface
     */
    public function getNext()
    {
        return new self($this->end);
    }

    /**
     * @return PeriodInterface
     */
    public function getPrevious()
    {
        $start = clone $this->begin;
        $start->sub(new \DateInterval('P1Y'));

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
     * @static
     * @param \DateTime $start
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('d-m') == '01-01';
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
        if (null === $this->current) {
            $this->current = new Month($this->begin);
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
        return $this->current->getBegin()->format('m');
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
     * Returns the year
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('Y');
    }
}
