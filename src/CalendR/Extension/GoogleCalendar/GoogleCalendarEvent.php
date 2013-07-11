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
    * @var string
    */
    protected $summary;

    /**
     * @var string
     */
    protected $status;


    /**
     * @var string
     */
    protected $htmlLink;


    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param string $calendarId
     * @param string $eventId
     * @param string $summary
     * @param string $status
     * @param string $htmlLink
     */
    public function __construct(\DateTime $begin, \DateTime $end, $calendarId, $eventId, $summary, $status, $htmlLink)
    {
        $this->calendarId = $calendarId;
        $this->begin = clone $begin;
        $this->end = clone $end;
        $this->id = $eventId;
        $this->summary = $summary;
        $this->status = $status;
        $this->htmlLink = $htmlLink;
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

    /**
     * @return string
     */
    public function getCalendarId()
    {
      return $this->calendarId;
    }

    /**
     * @return string
     */
    public function getHtmlLink()
    {
      return $this->htmlLink;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
      return $this->status;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
      return $this->summary;
    }
}
