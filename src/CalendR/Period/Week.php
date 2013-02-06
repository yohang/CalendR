<?php

namespace CalendR\Period;

/**
 * Represents a week
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Week extends PeriodAbstract implements \Iterator
{
    /**
     * @var null|PeriodInterface
     */
    private $current = null;

    /**
     * @param \DateTime $start
     * @param array|int $options
     * @throws Exception\NotAWeek
     *
     */
    public function __construct(\DateTime $start, $options = array())
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAWeek;
        }

        $this->begin = clone $start;
        $this->end = clone $start;
        $this->end->add(new \DateInterval('P7D'));

        parent::__construct($options);
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
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return true;
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
        if (!$this->valid()) {
            $dayClass = ($this->hasOption('day')) ? $this->getOption('day') : 'CalendR\Period\Day';
            $this->current = new $dayClass($this->begin, $this->options);
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
        return $this->current->getBegin()->format('d-m-Y');
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
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('P1W');
    }
}
