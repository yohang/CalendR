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
use CalendR\Period\PeriodFactory;
use CalendR\Period\PeriodFactoryInterface;
use CalendR\Period\Day;

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
     * @var PeriodFactoryInterface
     */
    private $periodFactory;

    function __construct(PeriodFactoryInterface $periodFactory = null)
    {
        if (null === $periodFactory){
            $periodFactory = new PeriodFactory();
        }
        $this->periodFactory = $periodFactory;
    }


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
     * @return Period\Year
     */
    public function getYear($yearOrStart)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return $this->periodFactory->create('year', $yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int      $month       number (1~12)
     *
     * @return Period\Month
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return $this->periodFactory->create('month', $yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $week
     *
     * @return Period\Week
     */
    public function getWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, '0', STR_PAD_LEFT)));
        }

        return $this->periodFactory->create('week', $yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int      $month
     * @param null|int      $day
     *
     * @return Period\Day
     */
    public function getDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return $this->periodFactory->create('day', $yearOrStart);
    }

    /**
     * @param Period\PeriodInterface $period
     * @param array                  $options
     *
     * @return \CalendR\Period\PeriodInterface <Event\EventInterface>
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
        foreach ($options as $option=>$value){
            if(property_exists($this, $option)) $this->$option = $value;
        }
    }

    /**
     * @param int $weekFirstDay
     * @deprecated - use periodFactory::setOption('weekFirstDay', $value)
     */
    public function setFirstWeekday($weekFirstDay)
    {
        $this->periodFactory->setOption('weekFirstDay', $weekFirstDay);
    }

     /**
     * @return int
     * @deprecated - use periodFactory->getOption('weekFirstDay')
     */
    public function getFirstWeekday()
    {
        return $this->periodFactory->getOption('weekFirstDay');
    }
}
