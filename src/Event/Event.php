<?php

declare(strict_types=1);

namespace CalendR\Event;

use CalendR\Event\Exception\InvalidEvent;

/**
 * Concrete implementation of AbstractEvent and in fact EventInterface.
 *
 * In most case, you'd better to implement your own events
 */
final class Event extends AbstractEvent
{
    protected \DateTimeInterface $begin;

    protected \DateTimeInterface $end;

    protected string $uid;

    public function __construct(
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        ?string $uid = null,
    ) {
        if (1 === $start->diff($end)->invert) {
            throw new InvalidEvent('Events usually start before they end');
        }

        $this->begin = clone $start;
        $this->end = clone $end;
        $this->uid = $uid ?? uniqid('event_', true);
    }

    #[\Override]
    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    #[\Override]
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    #[\Override]
    public function isEqualTo(EventInterface $event): bool
    {
        if (!$event instanceof self) {
            return false;
        }

        return $this->uid === $event->uid;
    }
}
