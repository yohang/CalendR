<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event\Provider;

use CalendR\Event\EventInterface;

/**
 * Basic event provider.
 * Add and retrieve events like with an array.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Basic implements ProviderInterface, \IteratorAggregate, \Countable
{
    /**
     * @var EventInterface[]
     */
    protected $events = array();

    /**
     * @{inheritDoc}
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $events = array();
        foreach ($this->events as $event) {
            if (
                $event->contains($begin) || $event->contains($end) ||
                (1 === $event->getBegin()->diff($begin)->invert && 0 === $event->getEnd()->diff($end)->invert)
            ) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * Adds an event to the provider
     *
     * @param EventInterface $event
     */
    public function add(EventInterface $event)
    {
        $this->events[] = $event;
    }

    /**
     * Returns all events
     *
     * @return array<EventInterface>
     */
    public function all()
    {
        return $this->events;
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->events);
    }

    /**
     * The return value is cast to an integer.
     *
     * @return int
     */
    public function count()
    {
        return count($this->events);
    }
}
