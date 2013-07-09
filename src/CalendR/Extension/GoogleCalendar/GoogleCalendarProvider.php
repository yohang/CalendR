<?php

namespace CalendR\Extension\GoogleCalendar;

use CalendR\Event\Provider\ProviderInterface;

use CalendR\Extension\GoogleCalendar\Exception\OptionConflict;

/**
 * Event provider.
 * Retrieve events from Google calendar id
 */
class GoogleCalendarProvider implements ProviderInterface
{
    /**
     * @var \Google_CalendarService
     */
    protected $service;

    /**
     * @var array
     */
    protected $calendars;

    /**
     * @param \Google_CalendarService $gcalService
     * @param array                   $calendarsIds
     */
    public function __construct(\Google_CalendarService $gcalService, array $calendarsIds = array())
    {
        $this->service = $gcalService;
        $this->calendars = $calendarsIds;
    }

    /**
     * Return the provider's Google calendar service
     *
     * @return \Google_CalendarService the provider's Google calendar service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Search ids of calendar from the Google service
     *
     * @return array
     */
    protected function discoverCalendars()
    {
        $calendars = array();
        $response = $this->service->calendarList->listCalendarList(array('fields'=>'items/id'));

        if (isset($response['items'])) {
            foreach ($response['items'] as $id) {
                $calendars[] = $id['id'];
            }
        }

        return $calendars;
    }

    /**
     * return the GoogleCalendarEvent corresponding to calendar#events $item
     *
     * @param array  $item
     * @param string $calendarId
     *
     * @return GoogleCalendarEvent
     */
    protected function createEvent($item, $calendarId)
    {
        if (isset($item['start']['timeZone']) && isset($item['end']['timeZone'])) {
            return new GoogleCalendarEvent(
                new \DateTime($item['start']['dateTime'], new \DateTimeZone($item['start']['timeZone'])),
                new \DateTime($item['end']['dateTime'], new \DateTimeZone($item['end']['timeZone'])),
                $calendarId,
                $item['id']
            );
        }

        return new GoogleCalendarEvent(
            new \DateTime($item['start']['dateTime']),
            new \DateTime($item['end']['dateTime']),
            $calendarId,
            $item['id']
        );
    }

    /**
     * Return the GoogleCalendarEvent array from the calendar#events $GoogleCalendar
     *
     * @param array $googleEvents
     * @param string $calendarId
     *
     * @return array
     */
    protected function createEvents($googleEvents, $calendarId)
    {
        $events = array();

        foreach ($googleEvents['items'] as $item) {
            $events[] = $this->createEvent($item, $calendarId);
        }

        return $events;
    }

    /**
     * Return the GoogleCalendarEvent array from the String $calendarId
     *
     * @param $calendarId
     * @param  \DateTime $begin
     * @param  \DateTime $end
     *
     * @return array[]
     */
    private function findEventsByCalendarId($calendarId, \DateTime $begin, \DateTime $end)
    {
        $optParams = array(
            'timeMin' => $begin->format('Y-m-d\TH:i:sP'),
            'timeMax' => $end->format('Y-m-d\TH:i:sP')
        );

        $response = $this->service->events->listEvents($calendarId, $optParams);

        if (isset($response['items'])) {
            return $this->createEvents($response, $calendarId);
        }

        return array();
    }

    /**
     * Return events that matches to $begin && $end from calendars' id find in $options
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array $options
     *
     * @return GoogleCalendarEvent[]
     * @throws Exception\OptionConflict
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $events = array();
        $providedCalendars = false;
        $discoverCalendars = false;
        $calendars = $this->calendars;

        if (isset($options['calendars']) && count($options['calendars']) > 0) {
            $providedCalendars = true;
        }

        if (isset($options['discover_calendars'])) {
            $discoverCalendars = $options['discover_calendars'];
        }

        if ($providedCalendars && $discoverCalendars) {
            throw new OptionConflict('calendars provided and calendar discover option activated at the same time');
        }

        if (count($calendars) > 0 && $discoverCalendars) {
            throw new OptionConflict('discover calendar option activated while provider already has calendars');
        }

        if ($providedCalendars) {
            $calendars = $options['calendars'];
        }

        if ($discoverCalendars) {
            $calendars = $this->discoverCalendars();
        }

        foreach ($calendars as $calendar) {
            $events = array_merge($events,$this->findEventsByCalendarId($calendar, $begin, $end));
        }

        return $events;
    }
}
