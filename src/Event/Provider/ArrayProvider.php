<?php

declare(strict_types=1);

namespace CalendR\Event\Provider;

use CalendR\Event\EventInterface;

/**
 * @implements \IteratorAggregate<int, EventInterface>
 */
final class ArrayProvider implements ProviderInterface, \IteratorAggregate, \Countable
{
    /**
     * @var EventInterface[]
     */
    protected array $events = [];

    #[\Override]
    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        $events = [];
        foreach ($this->events as $event) {
            if ($event->getBegin() < $end && $event->getEnd() > $begin) {
                $events[] = $event;
            }
        }

        return $events;
    }

    public function add(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return EventInterface[]
     */
    public function all(): array
    {
        return $this->events;
    }

    #[\Override]
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->events);
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->events);
    }
}
