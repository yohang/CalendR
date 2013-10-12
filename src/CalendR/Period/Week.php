<?php

namespace CalendR\Period;

/**
 * Represents a week
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Week extends PeriodAbstract implements \IteratorAggregate
{
    private $iterator;

    /**
     * @param \DateTime        $start
     * @param FactoryInterface $factory
     *
     * @throws Exception\NotAWeek
     */
    public function __construct(\DateTime $start, $factory = null)
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAWeek;
        }

        $this->begin = clone $start;
        $this->end = clone $start;
        $this->end->add(new \DateInterval('P7D'));

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

    public function getIterator()
    {
        if (null == $this->iterator) {
//            $this->iterator = new WeekIterator($this);
            $factory = $this->getFactory();
            $option = ($factory->getOption('weekdays_only')) ? 'weekdays_iterator_class' : 'week_iterator_class';
            $class = $factory->getOption($option);
            $this->iterator = new $class($this);
        }

        return $this->iterator;
    }

    /**
     * @param mixed $iterator
     */
    public function setIterator($iterator)
    {
        $this->iterator = $iterator;
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
