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

use CalendR\Event\EventInterface;
use CalendR\Period\PeriodInterface;

/**
 * Basic event collection.
 * Juste stores event as an array, and iterate over the array for retrieving.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Basic implements CollectionInterface
{
    /**
     * The events.
     *
     * @var EventInterface[]
     */
    protected $events;

    /**
     * @param EventInterface[] $events
     */
    public function __construct(array $events = array())
    {
        $this->events = $events;
    }

    /**
     * Adds an event to the collection.
     *
     * @param EventInterface $event
     */
    public function add(EventInterface $event)
    {
        $this->events[] = $event;
    }

    /**
     * Removes an event from the collection.
     *
     * @param EventInterface $event
     */
    public function remove(EventInterface $event)
    {
        foreach ($this->events as $key => $internalEvent) {
            if ($event->getUid() === $internalEvent->getUid()) {
                unset($this->events[$key]);
            }
        }
    }

    /**
     * Return all events;.
     *
     * @return EventInterface[]
     */
    public function all()
    {
        return $this->events;
    }

    /**
     * Returns if there is events corresponding to $index period.
     *
     * @param mixed $index
     *
     * @return bool
     */
    public function has($index)
    {
        return count($this->find($index)) > 0;
    }

    /**
     * Find events in the collection.
     *
     * @param mixed $index
     *
     * @return EventInterface[]
     */
    public function find($index)
    {
        $result = array();
        foreach ($this->events as $event) {
            if ($index instanceof PeriodInterface && $index->containsEvent($event)) {
                $result[] = $event;
            } elseif ($index instanceof \DateTime && $event->contains($index)) {
                $result[] = $event;
            }
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->events);
    }
}
