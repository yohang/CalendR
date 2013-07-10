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
use CalendR\Period\Factory;
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
    protected $options = array();

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
        return Factory::createYear($yearOrStart, $this->options);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int      $month       number (1~12)
     *
     * @return PeriodInterface
     */
    public function getMonth($yearOrStart, $month = null)
    {
        return Factory::createMonth($yearOrStart, $month, $this->options);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     *
     * @return PeriodInterface
     */
    public function getWeek($yearOrStart, $week = null)
    {
        return Factory::createWeek($yearOrStart, $week, $this->options);
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
        return Factory::createDay($yearOrStart, $month, $day, $this->options);
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
        foreach ($options as $name=>$value) {
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
        $this->options = Factory::resolveOptions($this->options);

        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * @param int $firstWeekday
     *
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link setOption('first_weekday')} instead.
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->setOption('first_weekday', $firstWeekday);
    }

    /**
     * @return int
     *
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link getOption('first_weekday')} instead.
     */
    public function getFirstWeekday()
    {
        return $this->getOption('first_weekday');
    }
}
