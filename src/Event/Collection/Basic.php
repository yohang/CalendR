<?php

declare(strict_types=1);

namespace CalendR\Event\Collection;

use CalendR\Event\EventInterface;
use CalendR\Period\PeriodInterface;

/**
 * Basic event collection.
 * Juste stores an event as an array and iterates over the array for retrieving.
 *
 * @implements \IteratorAggregate<int, EventInterface>
 * @implements CollectionInterface<int>
 */
final class Basic implements CollectionInterface, \IteratorAggregate
{
    /**
     * @param list<EventInterface> $events
     */
    public function __construct(
        protected array $events = [],
    ) {
    }

    #[\Override]
    public function add(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    #[\Override]
    public function remove(EventInterface $event): void
    {
        foreach ($this->events as $key => $internalEvent) {
            if ($event === $internalEvent) {
                unset($this->events[$key]);
            }
        }
    }

    #[\Override]
    public function all(): array
    {
        return $this->events;
    }

    #[\Override]
    public function has(PeriodInterface|\DateTimeInterface|string $index): bool
    {
        return \count($this->find($index)) > 0;
    }

    #[\Override]
    public function find(PeriodInterface|\DateTimeInterface|string $index): array
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

    #[\Override]
    public function count(): int
    {
        return \count($this->events);
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->events);
    }
}
