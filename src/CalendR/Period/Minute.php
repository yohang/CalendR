<?php

namespace CalendR\Period;

/**
 * Represents a minute.
 *
 * @author Zander Baldwin <mynameis@zande.rs>
 */
class Minute extends PeriodAbstract implements \Iterator
{
    /**
     * @var PeriodInterface
     */
    private $current;

    /**
     * @param \DateTime        $begin
     * @param FactoryInterface $factory
     *
     * @throws Exception\NotAMinute
     */
    public function __construct(\DateTime $begin, $factory = null)
    {
        parent::__construct($factory);
        if ($this->getFactory()->getStrictDates() && !self::isValid($begin)) {
            throw new Exception\NotAMinute();
        }

        // Not in strict mode, accept any timestamp and set the begin date back to the beginning of this period.
        $this->begin = clone $begin;
        $this->begin->setTime($this->begin->format('G'), $this->begin->format('i'), 0);

        $this->end = clone $begin;
        $this->end->add($this->getDateInterval());
    }

    /**
     * Returns the period as a DatePeriod.
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('PT1S'), $this->end);
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('s') == '00';
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        if (null === $this->current) {
            $this->current = $this->getFactory()->createSecond($this->begin);
        } else {
            $this->current = $this->current->getNext();
            if (!$this->contains($this->current->getBegin())) {
                $this->current = null;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return (int) $this->current->getBegin()->format('i');
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return null !== $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->current = null;
        $this->next();
    }

    /**
     * Returns the minute.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('i');
    }

    /**
     * Returns a \DateInterval equivalent to the period.
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('PT1M');
    }
}
