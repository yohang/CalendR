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

use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\EventInterface;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Event\Manager;
use CalendR\Period\Day;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Week;

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

    public function getYear($yearOrStart): Period\Year
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return $this->getFactory()->createYear($yearOrStart);
    }

    public function getMonth($yearOrStart, ?int $month = null): Month
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return $this->getFactory()->createMonth($yearOrStart);
    }

    public function getWeek($yearOrStart, ?int $week = null): Week
    {
        $factory = $this->getFactory();

        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, 0, STR_PAD_LEFT)));
        }

        return $factory->createWeek($factory->findFirstDayOfWeek($yearOrStart));
    }

    public function getDay($yearOrStart, ?int $month = null, ?int $day = null): Day
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
     * @throws NoProviderFound
     */
    public function getEvents(PeriodInterface $period, array $options = []): CollectionInterface
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
}
