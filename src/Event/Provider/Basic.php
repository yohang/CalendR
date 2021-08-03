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
    protected array $events = [];

    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        $events = [];
        foreach ($this->events as $event) {
            if (
                ($event->getBegin() >= $begin && $event->getBegin() < $end) ||
                ($event->getEnd() > $begin && $event->getEnd() <= $end) ||
                ($begin <= $event->getBegin() && $event->getEnd() <= $end) ||
                ($event->getBegin() <= $begin && $end <= $event->getEnd())
            ) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * Adds an event to the provider.
     */
    public function add(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Returns all events.
     *
     * @return EventInterface[]
     */
    public function all(): array
    {
        return $this->events;
    }

    /**
     * Retrieve an external iterator.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->events);
    }

    /**
     * The return value is cast to an integer.
     */
    public function count(): int
    {
        return count($this->events);
    }
}
