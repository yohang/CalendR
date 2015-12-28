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
use CalendR\Period\FactoryInterface;
use CalendR\Period\PeriodInterface;

/**
 * Factory class for calendar handling.
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
     * @var FactoryInterface
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
            $this->eventManager = new Manager();
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
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

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
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return $this->getFactory()->createMonth($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     *
     * @return PeriodInterface
     */
    public function getWeek($yearOrStart, $week = null)
    {
        $factory = $this->getFactory();

        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, 0, STR_PAD_LEFT)));
        }

        return $factory->createWeek($factory->findFirstDayOfWeek($yearOrStart));
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

        return $this->getFactory()->createDay($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     *
     * @return PeriodInterface
     */
    public function getHour($yearOrStart, $month = null, $day = null, $hour = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s %s:00', $yearOrStart, $month, $day, $hour));
        }

        return $this->getFactory()->createHour($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     *
     * @return PeriodInterface
     */
    public function getMinute($yearOrStart, $month = null, $day = null, $hour = null, $minute = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s %s:%s', $yearOrStart, $month, $day, $hour, $minute));
        }

        return $this->getFactory()->createMinute($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     *
     * @return PeriodInterface
     */
    public function getSecond($yearOrStart, $month = null, $day = null, $hour = null, $minute = null, $second = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(
                sprintf('%s-%s-%s %s:%s:%s', $yearOrStart, $month, $day, $hour, $minute, $second)
            );
        }

        return $this->getFactory()->createSecond($yearOrStart);
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
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        if (null === $this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    /**
     * @param int $firstWeekday
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->getFactory()->setFirstWeekday($firstWeekday);
    }

    /**
     * @return int
     */
    public function getFirstWeekday()
    {
        return $this->factory->getFirstWeekday();
    }

    /**
     * @return bool
     */
    public function getStrictDates()
    {
        return $this->getFactory()->getStrictDates();
    }

    /**
     * @param bool $strict
     */
    public function setStrictDates($strict)
    {
        $this->getFactory()->setStrictDates($strict);
    }
}
