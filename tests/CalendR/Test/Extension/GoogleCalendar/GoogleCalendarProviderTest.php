<?php

namespace CalendR\Test\Extension\GoogleCalendar;

use CalendR\Extension\GoogleCalendar\GoogleCalendarProvider;

class GoogleCalendarProviderTest extends \PHPUnit_Framework_TestCase
{

    protected $object;
    protected $object2;
    protected $object3;
    protected $gcalService;

    protected function setUp()
    {
        $this->gcalService = $this->getMock('\Google_CalendarService', array(), array(), '', false);
        $this->object = new GoogleCalendarProvider($this->gcalService);
        $this->object2 = new GoogleCalendarProvider($this->gcalService, array('calendar2'));
        $this->object3 = new GoogleCalendarProvider($this->gcalService, array('calendar1', 'calendar2'));
    }

    public static function providerGetEvents()
    {
        return array(
            array(
                new \DateTime('2013-01-01'),
                new \DateTime('2014-01-01'),
                array('calendars'=>array('calendar1',)),
                array('calendars'=>array('calendar2', 'calendar1')),
                array('items'=>array(array('id'=>'calendar3',),
                    ),
                ),
                '2013-02-01',
                '2013-03-01',
                '2014-03-01',
                '2014-04-01',
            ),
            array(
                new \DateTime('2013-02-20'),
                new \DateTime('2013-04-01'),
                array('calendars'=>array('calendar1',)),
                array('calendars'=>array('calendar2', 'calendar1')),
                array('items'=>array(array('id'=>'calendar3',),
                    ),
                ),
                '2013-02-01',
                '2013-03-01',
                '2013-04-02',
                '2014-04-01',
            ),
        );
    }

    /**
     * @dataProvider providerGetEvents
     */
    public function testGetEvents($begin, $end, $calendarArray1, $calendarArray2, $searchedCalendars,$event1Begin,
                                  $event1End, $event2Begin, $event2End)
    {
        $itemsArray1= array(
          'organizer'=>array(
            'id'=>"organizerId",
          ),
          'items'=>array(
            array(
              'kind' => 'calendar#event',
              'etag' => '"etag"',
              'id' =>  'id1234567489',
              'status' =>  'confirmed',
              'htmlLink' =>  'www.this.is/a/link',
              'created' =>  '2013-06-27T12:24:47.000Z',
              'updated' =>  '2013-06-27T12:24:47.463Z',
              'summary' =>  'lorem ipsum dolor sit amet',
              'creator' => array('email' =>  'johne@do.com'),
              'organizer' => array(
                'email' =>  'john@doe.fr',
                'displayName' =>  'John Doe',
                'self' =>  true
              ),
              'start' => array('dateTime' => $event1Begin),
              'end' => array('dateTime' =>  $event1End)
            )
          )
        );

        $itemsArray2= array(
          'organizer'=>array(
            'id'=>"organizerId",
          ),
          'items'=>array(
            array(
              'kind' => 'calendar#event',
              'etag' => '"etag"',
              'id' =>  'id1234567489',
              'status' =>  'confirmed',
              'htmlLink' =>  'www.this.is/a/link',
              'created' =>  '2013-06-27T12:24:47.000Z',
              'updated' =>  '2013-06-27T12:24:47.463Z',
              'summary' =>  'lorem ipsum dolor sit amet',
              'creator' => array ('email' =>  'johne@do.com'),
              'organizer' => array (
                'email' =>  'john@doe.com',
                'displayName' =>  'John Doe',
                'self' =>  true
              ),
              'start' => array ('dateTime' => $event1Begin),
              'end' => array ('dateTime' =>  $event1End)
            ),
            array(
              'kind' => 'calendar#event',
              'etag' => '"etag"',
              'id' =>  'id987654312',
              'status' =>  'tentative',
              'htmlLink' =>  'www.this.is/a/link',
              'created' =>  '2013-06-27T12:24:47.000Z',
              'updated' =>  '2013-06-27T12:24:47.463Z',
              'summary' =>  'lorem ipsum dolor sit amet',
              'creator' => array ('email' =>  'johne@do.com'),
              'organizer' => array (
                'email' =>  'john@doe.com',
                'displayName' =>  'John Doe',
                'self' =>  true
              ),
              'start' => array ('dateTime' => $event2Begin),
              'end' => array ('dateTime' =>  $event2End)
            )
          )
        );

        $googleEventsServiceResource = $this->getMock('Google_EventsServiceResource', array(), array(), '', false);
        $googleEventsServiceResource->expects($this->at(0))
            ->method('listEvents')
            ->with($this->matches('calendar1'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(1))
            ->method('listEvents')
            ->with($this->matches('calendar2'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(2))
            ->method('listEvents')
            ->with($this->matches('calendar1'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(3))
            ->method('listEvents')
            ->with($this->matches('calendar1'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(4))
            ->method('listEvents')
            ->with($this->matches('calendar2'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(5))
            ->method('listEvents')
            ->with($this->matches('calendar1'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(6))
            ->method('listEvents')
            ->with($this->matches('calendar2'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(7))
            ->method('listEvents')
            ->with($this->matches('calendar3'))
            ->will($this->returnValue($itemsArray2));
        $googleEventsServiceResource->expects($this->at(8))
            ->method('listEvents')
            ->with($this->matches('calendar1'))
            ->will($this->returnValue($itemsArray1));
        $googleEventsServiceResource->expects($this->at(9))
            ->method('listEvents')
            ->with($this->matches('calendar2'))
            ->will($this->returnValue($itemsArray1));

        $googleCalendarList = $this->getMock('Google_CalendarListServiceResource', array(), array(), '', false);
        $googleCalendarList->expects($this->at(0))
            ->method('listCalendarList')
            ->will($this->returnValue($searchedCalendars));

        $this->object->getService()->events = $googleEventsServiceResource;
        $this->object->getService()->calendarList = $googleCalendarList;

        $this->assertCount(1, $this->object->getEvents($begin, $end, $calendarArray1));
        $this->assertCount(2, $this->object->getEvents($begin, $end, $calendarArray2));
        $this->assertCount(1, $this->object2->getEvents($begin, $end, $calendarArray1));
        $this->assertCount(2, $this->object2->getEvents($begin, $end, $calendarArray2));
        $this->assertCount(1, $this->object2->getEvents($begin, $end));
        $this->assertCount(2, $this->object->getEvents($begin, $end, array('discover_calendars' => true)));
        $this->assertCount(2, $this->object3->getEvents($begin, $end));
        $this->setExpectedException('Exception');
        $this->assertCount(0, $this->object2->getEvents($begin, $end, array('discover_calendars' => true)));

    }
}
