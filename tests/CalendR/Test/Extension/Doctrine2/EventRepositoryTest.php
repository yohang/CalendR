<?php

namespace CalendR\Test\Extension\Doctrine2;

use CalendR\Test\BaseDoctrine2TestCase;
use CalendR\Test\Stubs\Event;
use CalendR\Test\Stubs\EventRepository;

class EventRepositoryTest extends BaseDoctrine2TestCase
{
    /**
     * @var EventRepository
     */
    protected $repo;

    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $this->markTestSkipped('You need PHP5.4 to use and test traits.');
        }

        $this->setUpDoctrine();
        $this->repo = $this->em->getRepository('CalendR\\Test\\Stubs\\Event');
    }

    public static function testGetEventsProvider()
    {
        return array(
            array('2012-01-01', '2012-01-05', array('event_during_begin', 'event_during_end', 'event_during_period', 'event_around_period')),
        );
    }

    /**
     * @dataProvider testGetEventsProvider
     */
    public function testGetEvents($begin, $end, array $eventUids)
    {
        $events = $this->repo->getEvents(new \DateTime($begin), new \DateTime($end));
        $this->assertSame(count($eventUids), count($events));
        foreach ($events as $event) {
            $this->assertContains($event->getUid(), $eventUids);
            unset($eventUids[array_search($event->getUid(), $eventUids)]);
        }
        $this->assertEmpty($eventUids);
    }

    public static function getStubEvents()
    {
        return array(
            // Events for first data-set, period : From 2012-01-01 to 2012-01-05
            array('event_during_begin', new \DateTime('2011-12-31'), new \DateTime('2012-01-02')),
            array('event_during_end', new \DateTime('2012-01-04'), new \DateTime('2012-01-07')),
            array('event_during_period', new \DateTime('2012-01-02'), new \DateTime('2012-01-03')),
            array('event_around_period', new \DateTime('2011-12-31'), new \DateTime('2012-01-07')),
            array('this_one_is_some_shit', new \DateTime('2011-12-31 09:00'), new \DateTime('2011-12-31 17:00')),
            array('this_one_too', new \DateTime('2012-01-09'), new \DateTime('2012-01-10')),
        );
    }
}
