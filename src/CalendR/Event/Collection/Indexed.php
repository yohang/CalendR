<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event\Collection;

use CalendR\Period\PeriodInterface,
    CalendR\Event\EventInterface;

class Indexed implements CollectionInterface
{
    /**
     * @var array|array|EventInterface
     */
    protected $events;

    /**
     * Event count
     *
     * @var int
     */
    protected $count = 0;

    /**
     * The function used to index events.
     * Takes a \DateTime in parameter and must return an array index for this value.
     *
     * By default :
     * ```php
     *  function(\DateTime $dateTime) {
     *      return $dateTime->format('Y-m-d');
     *  }
     * ```
     *
     * @var callable
     */
    protected $indexFunction;

    /**
     * @param array $events
     * @param null $callable
     */
    public function __construct(array $events = array(), $callable = null)
    {
        if (is_callable($callable)) {
            $this->indexFunction = $callable;
        } else {
            $this->indexFunction = function(\DateTime $dateTime) {
                return $dateTime->format('Y-m-d');
            };
        }

        foreach ($events as $event) {
            $this->add($event);
        }
    }

    /**
     * Adds an event to the collection
     *
     * @param CalendR\Event\EventInterface $event
     */
    public function add(EventInterface $event)
    {
        $index = $this->computeIndex($event);
        if (isset($this->events[$index])) {
            $this->events[$index][] = $event;
        } else {
            $this->events[$index] = array($event);
        }

        $this->count++;
    }

    /**
     * Removes an event from the collection
     *
     * @param CalendR\Event\EventInterface $event
     */
    public function remove(EventInterface $event)
    {
        $index = $this->computeIndex($event);
        if (isset($this->events[$index])) {
            foreach ($this->events[$index] as $key => $event) {
                if ($event->getUid() == $event->getUid()) {
                    unset($this->events[$index][$key]);
                    $this->count--;
                }
            }
        }
    }

    /**
     * returns events
     *
     * @param $index
     * @return array
     */
    public function find($index)
    {
        if ($index instanceof PeriodInterface) {
            $index = $index->getBegin();
        }
        if ($index instanceof \DateTime) {
            $index = $this->computeIndex($index);
        }

        return isset($this->events[$index]) ? $this->events[$index] : array();
    }

    /**
     * Computes event index
     *
     * @param \CalendR\Event\EventInterface $event
     * @return string
     */
    protected function computeIndex($toCompute)
    {
        if ($toCompute instanceof EventInterface) {
            $toCompute = $toCompute->getBegin();
        }
        $function = $this->indexFunction;

        return $function($toCompute);
    }

    /**
     * @{inheritDoc}
     */
    public function count()
    {
        return $this->count;
    }
}
