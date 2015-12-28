<?php

namespace CalendR\Period;

/**
 * Represents a week.
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
     * @param \DateTime        $start
     * @param FactoryInterface $factory
     *
     * @throws Exception\NotAWeek
     */
    public function __construct(\DateTime $start, $factory = null)
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAWeek();
        }

        $this->begin = clone $start;
        $this->end = clone $start;
        $this->end->add($this->getDateInterval());

        parent::__construct($factory);
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->begin->format('W');
    }

    /**
     * Returns the period as a DatePeriod.
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
        if (!$this->valid()) {
            $this->current = $this->getFactory()->createDay($this->begin);
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
        return $this->current->getBegin()->format('d-m-Y');
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
     * Returns the week number.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('W');
    }

    /**
     * Returns a \DateInterval equivalent to the period.
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('P1W');
    }
}
