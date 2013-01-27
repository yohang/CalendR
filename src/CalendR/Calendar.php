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

    private $dayClass   = 'CalendR\Period\Day';
    private $weekClass  = 'CalendR\Period\Week';
    private $monthClass = 'CalendR\Period\Month';
    private $yearClass  = 'CalendR\Period\Year';

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

        return new $this->yearClass($yearOrStart, array('monthClass' => $this->monthClass));
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

        return new $this->monthClass($yearOrStart,
            array('dayClass'=> $this->dayClass, 'WeekClass' => $this->weekClass));
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

        return new $this->weekClass($yearOrStart, array('dayClass' => $this->dayClass));
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

        return new $this->dayClass($yearOrStart);
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
     * @param array $classes
     */
    public function setClasses(array $classes)
    {
        foreach ($classes as $class=>$name){
            if(property_exists($this, $class)) $this->$class = $name;
        }
    }
}
