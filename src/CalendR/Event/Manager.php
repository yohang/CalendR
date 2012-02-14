<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event;

use CalendR\Period\PeriodInterface,
    CalendR\Event\Provider\ProviderInterface;

/**
 * Manage events and providers
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Manager implements \IteratorAggregate, \Countable
{
    /**
     * @var array|EventInterface
     */
    private $events = array();

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @param array|EventInterface $event
     * @return Manager
     */
    public function add($events)
    {
        $events = is_array($events) ? $events : array($events);
        foreach ($events as $event) {
            if (!$event instanceof EventInterface) {
                throw new \InvalidArgumentException('Events must implement \\CalendR\\Event\\EventInterface.');
            }
            $this->events[$event->getUid()] = $event;
        }

        return $this;
    }

    /**
     * @param string $uid event unique identifier
     * @return bool
     */
    public function has($uid)
    {
        return isset($this->events[$uid]);
    }

    /**
     * @param string $uid event unique identifier
     * @return EventInterface
     * @throws Exception\NotFound
     */
    public function get($uid)
    {
        if (!$this->has($uid)) {
            throw new Exception\NotFound;
        }

        return $this->events[$uid];
    }

    /**
     * @param string $uid event unique identifier
     * @return Manager
     * @throws Exception\NotFound
     */
    public function remove($uid)
    {
        if (!$this->has($uid)) {
            throw new Exception\NotFound;
        }

        unset($this->events[$uid]);

        return $this;
    }

    /**
     * @return array|EventInterface
     */
    public function all()
    {
        return $this->events;
    }

    /**
     * find events that matches the given period (during or over)
     *
     * @param \CalendR\Period\PeriodInterface $period
     * @return array|EventInterface
     */
    public function find(PeriodInterface $period)
    {
        if (null !== $this->provider) {
            $this->add($this->provider->getEvents($period->getBegin(), $period->getEnd()));
        }

        $events = array();

        foreach ($this->all() as $event) {
            if ($event->containsPeriod($period)
                || $event->isDuring($period)
                || $period->contains($event->getBegin())
                || $period->contains($event->getEnd())
            ) {
                $events[] = $event;
            }
        }

        return $events;
    }



    /**
     * \IteratorAggregate implementation
     * @return \ArrayIterator
     */
    public function getIterator()
    {
       return new \ArrayIterator($this->events);
    }

    /**
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->events);
    }

    /**
     * @param \CalendR\Event\ProviderInterface $provider
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \CalendR\Event\ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
