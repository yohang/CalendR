<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

/**
 * Represents a Day.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Day extends PeriodAbstract implements \Iterator
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 0;

    /**
     * @var PeriodInterface
     */
    private $current;

    /**
     * @param \DateTime        $begin
     * @param FactoryInterface $factory
     */
    public function __construct(\DateTime $begin, $factory = null)
    {
        parent::__construct($factory);
        if ($this->getFactory()->getStrictDates() && !self::isValid($begin)) {
            throw new Exception\NotADay();
        }

        // Not in strict mode, accept any timestamp and set the begin date back to the beginning of this period.
        $this->begin = clone $begin;
        $this->begin->setTime(0, 0, 0);

        $this->end = clone $this->begin;
        $this->end->add($this->getDateInterval());
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
     * Returns the day name (probably in english).
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('l');
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        return $start->format('H:i:s') == '00:00:00';
    }

    /**
     * Returns a \DateInterval equivalent to the period.
     *
     * @static
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('P1D');
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
            $this->current = $this->getFactory()->createHour($this->begin);
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
        return (int) $this->current->getBegin()->format('G');
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
}
