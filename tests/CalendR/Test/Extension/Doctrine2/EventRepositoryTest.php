<?php

namespace CalendR\Test\Extension\Doctrine2;

use CalendR\Event\Event;
use CalendR\Test\Stubs\EventRepository;

class EventRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventRepository
     */
    protected $repo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $classMetadata;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $qb;

    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $this->markTestSkipped('You need PHP5.4 to use and test traits.');
        }

        $this->em            = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $this->classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();
        $this->qb            = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repo          = new EventRepository($this->em, $this->classMetadata);
    }

    public static function testGetEventsProvider()
    {
        return array(
            array(
                '2012-01-01',
                '2012-01-05',
                array(
                    new Event('event_during_begin', new \DateTime('2011-12-25'), new \DateTime('2012-01-01')),
                    new Event('event_during_end', new \DateTime('2012-01-04'), new \DateTime('2012-01-06')),
                    new Event('event_during_period', new \DateTime('2012-01-03'), new \DateTime('2012-01-04')),
                    new Event('event_around_period', new \DateTime('2011-12-25'), new \DateTime('2012-01-06'))
                )
            ),
        );
    }

    /**
     * @dataProvider testGetEventsProvider
     */
    public function testGetEvents($begin, $end, array $providedEvents)
    {
        $expr  = $this->getMock('Doctrine\ORM\Query\Expr');
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('_doExecute', 'getSQL', 'execute', 'getResult'))
            ->getMock();
        $query->expects($this->once())->method('getResult')->will($this->returnValue($providedEvents));
        $this->em->expects($this->once())->method('createQueryBuilder')->will($this->returnValue($this->qb));
        $this->qb->expects($this->once())->method('select')->will($this->returnValue($this->qb));
        $this->qb->expects($this->once())->method('from')->will($this->returnValue($this->qb));
        $this->qb->expects($this->once())->method('andWhere')->will($this->returnValue($this->qb));
        $this->qb->expects($this->once())->method('getQuery')->will($this->returnValue($query));
        $this->qb->expects($this->atLeastOnce())->method('expr')->will($this->returnValue($expr));
        $expr->expects($this->once())->method('orX');
        $expr->expects($this->exactly(4))->method('andX');

        $events = $this->repo->getEvents(new \DateTime($begin), new \DateTime($end));
        $this->assertSame($providedEvents, $events);
    }
}
