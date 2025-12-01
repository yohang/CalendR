<?php

declare(strict_types=1);

namespace CalendR\Event;

use CalendR\Event\Exception\InvalidEvent;

/**
 * Concrete implementation of EventInterface.
 *
 * In most cases, you'd better to implement your own events with the help of EventTrait
 */
final class Event implements EventInterface
{
    use EventTrait;

    protected \DateTimeInterface $begin;

    protected \DateTimeInterface $end;

    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        if (1 === $start->diff($end)->invert) {
            throw new InvalidEvent('Events usually start before they end');
        }

        $this->begin = \DateTimeImmutable::createFromInterface($start);
        $this->end = \DateTimeImmutable::createFromInterface($end);
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
}
