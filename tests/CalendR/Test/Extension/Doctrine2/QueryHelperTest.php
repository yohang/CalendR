<?php

namespace CalendR\Test\Extension\Doctrine2;

use CalendR\Test\BaseDoctrine2TestCase;
use CalendR\Test\Stubs\Event;
use CalendR\Extension\Doctrine2\QueryHelper;

class QueryHelperTest extends BaseDoctrine2TestCase
{
    public function setUp()
    {
        $this->setUpDoctrine();
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
        $events = QueryHelper::addEventQuery(
                $this->em->createQueryBuilder()->select('evt')->from('CalendR\\Test\\Stubs\\Event', 'evt'),
                'evt.begin',
                'evt.end',
                new \DateTime($begin),
                new \DateTime($end)
            )
            ->getQuery()
            ->getResult()
        ;

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
