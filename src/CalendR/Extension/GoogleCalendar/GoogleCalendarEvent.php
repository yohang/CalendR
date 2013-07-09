<?php

namespace CalendR\Extension\GoogleCalendar;

use CalendR\Event\AbstractEvent;

class GoogleCalendarEvent extends AbstractEvent
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $calendarId;

    /**
     * @var \DateTime
     */
    protected $begin;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param string    $calendarId
     * @param string    $eventId
     */
    public function __construct(\DateTime $begin, \DateTime $end, $calendarId, $eventId)
    {
        $this->calendarId = $calendarId;
        $this->begin = clone $begin;
        $this->end = clone $end;
        $this->id = $eventId;
    }

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return $this->id;
    }

    /**
     * Returns the event begin
     *
     * @return \DateTime event begin
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Returns the event end
     *
     * @return \DateTime event end
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Returns the event id
     *
     * @return string event id
     */
    public function getId()
    {
        return $this->id;
    }
}
