<?php

declare(strict_types=1);

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
 */
class Basic implements CollectionInterface, \IteratorAggregate
{
    /**
     * The events.
     *
     * @var list<EventInterface>
     */
    protected array $events;

    /**
     * @param list<EventInterface> $events
     */
    public function __construct(array $events = [])
    {
        $this->events = $events;
    }

    /**
     * Adds an event to the collection.
     */
    public function add(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Removes an event from the collection.
     */
    public function remove(EventInterface $event): void
    {
        foreach ($this->events as $key => $internalEvent) {
            if ($event->isEqualTo($internalEvent)) {
                unset($this->events[$key]);
            }
        }
    }

    /**
     * Return all events;.
     *
     * @return EventInterface[]
     */
    public function all(): array
    {
        return $this->events;
    }

    /**
     * Returns if there is events corresponding to $index period.
     */
    public function has(mixed $index): bool
    {
        return \count($this->find($index)) > 0;
    }

    /**
     * Find events in the collection.
     *
     * @return EventInterface[]
     */
    public function find(mixed $index): array
    {
        $result = [];
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
        return \count($this->events);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->events);
    }
}
