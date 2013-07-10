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

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param  array|int                 $options
     * @throws Exception\NotAWeekday
     * @throws Exception\InvalidArgument
     */
    public function __construct($options = array())
    {
        if (is_numeric($options)) { // for backwards compatibility
            $options = array('first_weekday' => $options);
        }
        if (!is_array($options)) {
            throw new Exception\InvalidArgument('options parameter must be integer or array');
        }
        if (isset($options['first_weekday']) && ($options['first_weekday'] < 0 || $options['first_weekday'] > 6)) {
            throw new Exception\NotAWeekday(
                sprintf('"%s" is not a valid day. Days are between 0 (Sunday) and 6 (Friday)', $options['first_weekday'])
            );
        }
        $this->setOptions($options);
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
     * @return bool true if the period contains this date
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
        return new static($this->end, $this->options);
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

        return new static($start, $this->options);
    }

    /**
     * @param  int  $firstWeekday
     * @return void
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link setOption('first_weekday')} instead.
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->options['first_weekday'] = $firstWeekday;
    }

    /**
     * @return int
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link getOption('first_weekday')} instead.
     */
    public function getFirstWeekday()
    {
        return $this->getOption('first_weekday');
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        foreach ($options as $name=>$value) {
            $this->setOption($name, $value);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getOption($name)
    {
        $this->options = Factory::resolveOptions($this->options);

        return (isset($this->options[$name])) ? $this->options[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }
}
