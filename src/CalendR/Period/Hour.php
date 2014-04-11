<?php

namespace CalendR\Period;

/**
 * Represents an hour
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 */
class Hour extends PeriodAbstract implements \Iterator
{
    /**
     * @var PeriodInterface
     */
    private $current;

    /**
     * @param \DateTime        $begin
     * @param FactoryInterface $factory
     *
     * @throws Exception\NotAYear
     */
    public function __construct(\DateTime $begin, $factory = null)
    {
        if (!self::isValid($begin)) {
            throw new Exception\NotAnHour;
        }

        $this->begin = clone $begin;
        $this->end = clone $begin;
        $this->end->add($this->getDateInterval());

        parent::__construct($factory);
    }

    /**
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1M'), $this->end);
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('i:s') == '00:00';
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
            $this->current = $this->getFactory()->createMinute($this->begin);
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
        return (int) $this->current->getBegin()->format('G');
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
     * Returns the hour
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('G');
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('PT1H');
    }
}
