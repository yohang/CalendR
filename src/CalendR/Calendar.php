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
     * @var \CalendR\Event\Manager
     */
    private $eventManager;

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
     * @return Period\Year
     */
    public function getYear($yearOrStart)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return new Period\Year($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int      $month       number (1~12)
     * @return \CalendR\Period\Month
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return new Period\Month($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     * @return Period\Week
     */
    public function getWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, '0', STR_PAD_LEFT)));
        }

        return new Period\Week($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     */
    public function getDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return new Period\Day($yearOrStart);
    }

    public function getEvents(PeriodInterface $period, array $options = array())
    {
        return $this->eventManager->find($period, $options);
    }

}
