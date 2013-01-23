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
    /**
     * @var \DateTime
     */
    protected $begin;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @var string
     */
    protected $uid;

    public function __construct($uid, \DateTime $start, \DateTime $end)
    {
        if ($start->diff($end)->invert == 1) {
            throw new \InvalidArgumentException('Events usually start before they end');
        }

        $this->uid = $uid;
        $this->begin = clone $start;
        $this->end = clone $end;
    }

    /**
     * Returns an unique identifier for the Event.
     * Could be any string, but MUST to be unique.
     *   ex : 'event-8', 'meeting-43'
     *
     * @return string an unique event identifier
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Returns the event begin
     * @return \DateTime event begin
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Returns the event end
     * @return \DateTime event end
     */
    public function getEnd()
    {
        return $this->end;
    }
}
