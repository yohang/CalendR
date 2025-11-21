<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event;

/**
 * Concrete implementation of AbstractEvent and in fact EventInterface.
 *
 * In most case, you'd better to implement your own events
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Event extends AbstractEvent
{
    protected \DateTimeInterface $begin;

    protected \DateTimeInterface $end;

    public function __construct(protected string $uid, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        if (1 === $start->diff($end)->invert) {
            throw new \InvalidArgumentException('Events usually start before they end');
        }
        $this->begin = clone $start;
        $this->end   = clone $end;
    }

    /**
     * Returns an unique identifier for the Event.
     * Could be any string, but MUST to be unique.
     *   ex : 'event-8', 'meeting-43'.
     *
     * @return string an unique event identifier
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * Returns the event begin.
     */
    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    /**
     * Returns the event end.
     */
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }
}
