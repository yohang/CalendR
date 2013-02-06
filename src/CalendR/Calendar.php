<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR;

use CalendR\Event\Manager;
use CalendR\Period\PeriodInterface;

/**
 * Factory class for calendar handling
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Calendar
{
    /**
     * @var Manager
     */
    private $eventManager;

    /**
     * @var array
     */
    protected $options = array(
        'first_day' => Period\Day::MONDAY,
        'day'       => 'CalendR\Period\Day',
        'week'      => 'CalendR\Period\Week',
        'month'     => 'CalendR\Period\Month',
        'year'      => 'CalendR\Period\Year',
        'range'     => 'CalendR\Period\Range',
    );

    /**
     * @param Manager $eventManager
     */
    public function setEventManager(Manager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return Manager
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->eventManager = new Manager;
        }

        return $this->eventManager;
    }

    /**
     * @param \DateTime|int $yearOrStart
     *
     * @return PeriodInterface
     */
    public function getYear($yearOrStart)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }
        $yearClass = $this->getOption('year');

        return new $yearClass($yearOrStart, $this->options);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int      $month       number (1~12)
     *
     * @return PeriodInterface
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }
        $monthClass = $this->getOption('month');

        return new $monthClass($yearOrStart, $this->options);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     *
     * @return PeriodInterface
     */
    public function getWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, '0', STR_PAD_LEFT)));
        }
        $weekClass = $this->getOption('week');

        return new $weekClass($yearOrStart, $this->options);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     *
     * @return PeriodInterface
     */
    public function getDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }
        $dayClass = $this->getOption('day');

        return new $dayClass($yearOrStart, $this->options);
    }

    /**
     * @param Period\PeriodInterface $period
     * @param array                  $options
     *
     * @return array<Event\EventInterface>
     */
    public function getEvents(PeriodInterface $period, array $options = array())
    {
        return $this->getEventManager()->find($period, $options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name=>$value){
            $this->setOption($name, $value);
        };
    }

    /**
     * @param $name string
     * @param $value mixed
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @param $name string
     * @return mixed
     */
    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * @param int $weekFirstDay
     * @deprecated - use setOption('first_day', $value)
     */
    public function setFirstWeekday($weekFirstDay)
    {
        $this->setOption('first_day', $weekFirstDay);
    }

    /**
     * @return int
     * @deprecated - use getOption('first_day')
     */
    public function getFirstWeekday()
    {
        return $this->getOption('first_day');
    }
}
