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

use CalendR\Event\EventInterface;

/**
 * An abstract class that represent a date period and provide some base helpers
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
abstract class PeriodAbstract implements PeriodInterface
{
    /**
     * @var \DateTime
     */
    protected $begin;

    /**
     * @var \DateTime
     */
    protected $end;

    /** @var PeriodFactoryInterface */
    protected $factory;

    /**
     * @param \DateTime $start
     * @param null|int|PeriodFactoryInterface $factory
     * @throws Exception\InvalidFactoryArgument
     *
     */
    public function __construct(\DateTime $start, $factory = null)
    {
        $this->begin = clone $start;
        if (null === $factory) $factory = new PeriodFactory();
        if (is_integer($factory)){
            $weekFirstDay = $factory;
            $factory = new PeriodFactory();
            $factory->setOption('weekFirstDay', $weekFirstDay);
        }
        if ($factory instanceof PeriodFactoryInterface){
            $this->factory = $factory;
        } else {
            throw new Exception\InvalidFactoryArgument();
        }
    }

    /**
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Checks if the given period is contained in the current period
     *
     * @param \DateTime $date
     *
     * true if the period contains this date
     * @return bool
     */
    public function contains(\DateTime $date)
    {
        return $this->begin <= $date && $date < $this->end;
    }

    /**
     * Checks if a period is equals to an other
     *
     * @param PeriodInterface $period
     *
     * @return bool
     */
    public function equals(PeriodInterface $period)
    {
        return
            $period instanceof static &&
            $this->begin->format('Y-m-d-H-i-s') === $period->getBegin()->format('Y-m-d-H-i-s')
        ;
    }

    /**
     * Returns true if the period include the other period
     * given as argument
     *
     * @param PeriodInterface $period
     * @param bool            $strict
     *
     * @return bool
     */
    public function includes(PeriodInterface $period, $strict = true)
    {
        if (true === $strict) {
            return $this->getBegin() <= $period->getBegin() && $this->getEnd() >= $period->getEnd();
        }

        return
            $this->includes($period, true) ||
            $period->includes($this, true) ||
            $this->contains($period->getBegin()) ||
            $this->contains($period->getEnd())
        ;
    }

    /**
     * Returns if $event is during this period.
     * Non strict. Must return true if :
     *  * Event is during period
     *  * Period is during event
     *  * Event begin is during Period
     *  * Event end is during Period
     *
     * @param EventInterface $event
     *
     * @return boolean
     */
    public function containsEvent(EventInterface $event)
    {
        return
            $event->containsPeriod($this) ||
            $event->isDuring($this) ||
            $this->contains($event->getBegin()) ||
            $this->contains($event->getEnd())
        ;
    }

    /**
     * Format the period to a string
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format)
    {
        return $this->begin->format($format);
    }

    /**
     * Returns if the current period is the current one
     *
     * @return bool
     */
    public function isCurrent()
    {
        return $this->contains(new \DateTime);
    }

    /**
     * Gets the next period of the same type
     *
     * @return PeriodInterface
     */
    public function getNext()
    {
        return new static($this->end, $this->factory);
    }

    /**
     * Gets the previous period of the same type
     *
     * @return PeriodInterface
     */
    public function getPrevious()
    {
        $start = clone $this->begin;
        $start->sub(static::getDateInterval());

        return new static($start, $this->factory);
    }

    /**
     * @param int $firstWeekday
     * @deprecated - use  periodFactory::setOption('weekFirstDay', $value)
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->factory->setOption('weekFirstDay', $firstWeekday);
    }

    /**
     * @return int
     * @deprecated - use periodFactory::getOption('weekFirstDay')
     */
    public function getFirstWeekday()
    {
        return $this->factory->getOption('weekFirstDay');
    }
}
