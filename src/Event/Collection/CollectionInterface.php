<?php

declare(strict_types=1);

namespace CalendR\Event\Collection;

use CalendR\Event\EventInterface;
use CalendR\Period\PeriodInterface;

/**
 * Represents an event collection.
 *
 * @template TKey
 *
 * @extends \Traversable<TKey, EventInterface>
 */
interface CollectionInterface extends \Countable, \Traversable
{
    /**
     * Adds an event to the collection.
     */
    public function add(EventInterface $event): void;

    /**
     * Removes an event from the collection.
     */
    public function remove(EventInterface $event): void;

    /**
     * Return all events;.
     *
     * @return list<EventInterface>
     */
    public function all(): array;

    /**
     * Returns if there is events corresponding to $index period.
     */
    public function has(PeriodInterface|\DateTimeInterface|string $index): bool;

    /**
     * Find events in the collection.
     *
     * @return list<EventInterface>
     */
    public function find(PeriodInterface|\DateTimeInterface|string $index): array;
}
