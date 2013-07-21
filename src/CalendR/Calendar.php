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
     * @var Factory
     */
    protected $factory;

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
     * @param $yearOrStart
     *
     * @return Period\Year
     */
    public function getYear($yearOrStart)
    {
        return $this->getFactory()->createYear($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int      $month       number (1~12)
     *
     * @return PeriodInterface
     */
    public function getMonth($yearOrStart, $month = null)
    {
        return $this->getFactory()->createMonth($yearOrStart, $month);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     *
     * @return PeriodInterface
     */
    public function getWeek($yearOrStart, $week = null)
    {
        return $this->getFactory()->createWeek($yearOrStart, $week);
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
        return $this->getFactory()->createDay($yearOrStart, $month, $day);
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
     * @param Factory $factory
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        if (null === $this->factory) {
            $this->factory = new Factory;
        }

        return $this->factory;
    }

    /**
     * @param int $firstWeekday
     *
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link setOption('first_weekday')} instead.
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->getFactory()->setOption('first_weekday', $firstWeekday);
    }

    /**
     * @return int
     *
     * @deprecated Deprecated since version 1.1, to be removed in 2.0. Use {@link getOption('first_weekday')} instead.
     */
    public function getFirstWeekday()
    {
        return $this->factory->getOption('first_weekday');
    }
}
