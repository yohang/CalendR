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
        $begin = new \DateTime($item['start']['dateTime']);
        $end = new \DateTime($item['end']['dateTime']);

        if (isset($item['start']['timeZone']) && isset($item['end']['timeZone'])) {
                $begin = new \DateTime($item['start']['dateTime'], new \DateTimeZone($item['start']['timeZone']));
                $end = new \DateTime($item['end']['dateTime'], new \DateTimeZone($item['end']['timeZone']));
        }

        return new GoogleCalendarEvent(
            $begin,
            $end,
            $calendarId,
            $item['id'],
            $item['summary'],
            $item['status'],
            $item['htmlLink']
        );
    }

    /**
     * Return the GoogleCalendarEvent array from the calendar#events $GoogleCalendar
     *
     * @param array  $googleEvents
     * @param string $calendarId
     * @param array  $optParams
     *
     * @return array
     */
    protected function createEvents($googleEvents, $calendarId, array $optParams)
    {
        $events     = array();
        $recurrings = array();
        foreach ($googleEvents['items'] as $item) {
          if(!(isset($item['start']) && isset($item['end']))) {
              continue;
          }

          if ($this->isRecurring($item)) {
              $recurrings[] = $item;

              continue;
          }

          $events[] = $this->createEvent($item, $calendarId);
        }

        foreach ($recurrings as $recurring) {
            $instances = $this->createEvents(
                $this->service->events->instances($calendarId, $recurring['id'], $optParams),
                $calendarId,
                $optParams
            );

            foreach ($instances as $instance) {
                $events[] = $instance;
            }
        }

        return $events;
    }

    /**
     * Return events that matches to $begin && $end from calendars' id find in $options
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array     $options
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

    /**
     * Return the GoogleCalendarEvent array from the String $calendarId
     *
     * @param $calendarId
     * @param \DateTime $begin
     * @param \DateTime $end
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
        return $this->createEvents($response, $calendarId, $optParams);
      }

      return array();
    }

    /**
     * @param array $event
     *
     * @return bool
     */
    private function isRecurring(array $event)
    {
        return isset($event['recurrence']) && count($event['recurrence']) > 0;
    }
}
