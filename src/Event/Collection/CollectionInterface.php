<?php

declare(strict_types=1);

namespace CalendR\Event\Collection;

use CalendR\Event\EventInterface;

/**
 * Represents an event collection.
 */
interface CollectionInterface extends \Countable, \Traversable
{
    /**
     * Adds an event to the collection.
     */
    public function add(EventInterface $event);

    /**
     * Removes an event from the collection.
     */
    public function remove(EventInterface $event);

    /**
     * Return all events;.
     *
     * @return list<EventInterface>
     */
    public function all(): array;

    /**
     * Returns if there is events corresponding to $index period.
     */
    public function has(mixed $index): bool;

    /**
     * Find events in the collection.
     *
     * @return list<EventInterface>
     */
    public function find(mixed $index): array;
}
