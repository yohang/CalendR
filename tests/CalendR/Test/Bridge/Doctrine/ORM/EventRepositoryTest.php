<?php

namespace CalendR\Test\Bridge\Doctrine\ORM;

use CalendR\Event\Event;
use CalendR\Test\Stubs\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EventRepositoryTest extends TestCase
{
    /**
     * @var EventRepository
     */
    protected $repo;

    /**
     * @var MockObject|EntityManagerInterface
     */
    protected $em;

    /**
     * @var MockObject|ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var MockObject|QueryBuilder
     */
    protected $qb;

    public function setUp()
    {
        $this->em            = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->classMetadata = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $this->qb            = $this->getMockBuilder(QueryBuilder::class)->disableOriginalConstructor()->getMock();
        $this->repo          = new EventRepository($this->em, $this->classMetadata);
    }

    public static function getEventsProvider()
    {
        return [
            [
                '2012-01-01',
                '2012-01-05',
                [
                    new Event('event_during_begin', new \DateTime('2011-12-25'), new \DateTime('2012-01-01')),
                    new Event('event_during_end', new \DateTime('2012-01-04'), new \DateTime('2012-01-06')),
                    new Event('event_during_period', new \DateTime('2012-01-03'), new \DateTime('2012-01-04')),
                    new Event('event_around_period', new \DateTime('2011-12-25'), new \DateTime('2012-01-06')),
                ],
            ],
        ];
    }

    /**
     * @dataProvider getEventsProvider
     */
    public function testGetEvents($begin, $end, array $providedEvents)
    {
        $expr  = $this->getMockBuilder('Doctrine\ORM\Query\Expr')->getMock();
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
                      ->disableOriginalConstructor()
                      ->setMethods(['_doExecute', 'getSQL', 'execute', 'getResult'])
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
