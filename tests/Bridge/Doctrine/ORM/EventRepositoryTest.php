<?php

namespace CalendR\Test\Bridge\Doctrine\ORM;

use CalendR\Event\Event;
use CalendR\Test\Stubs\EventRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Query\Expr;

class EventRepositoryTest extends TestCase
{
    protected EventRepository $repo;

    protected EntityManagerInterface $em;

    protected ClassMetadata $classMetadata;

    protected QueryBuilder $qb;

    public function setUp(): void
    {
        $this->em            = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->classMetadata = $this->getMockBuilder(ClassMetadata::class)->setConstructorArgs(['Event'])->getMock();
        $this->qb            = $this->getMockBuilder(QueryBuilder::class)->disableOriginalConstructor()->getMock();

        $this->repo          = new EventRepository($this->em, $this->classMetadata);
    }

    public static function getEventsProvider(): array
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
    public function testGetEvents($begin, $end, array $providedEvents): void
    {
        $expr  = $this->getMockBuilder(Expr::class)->getMock();
        $query = $this->getMockBuilder((new \ReflectionClass(Query::class))->isFinal() ? AbstractQuery::class : Query::class)
                      ->disableOriginalConstructor()
                      ->onlyMethods(['_doExecute', 'getSQL', 'execute', 'getResult'])
                      ->getMock();

        $query->expects($this->once())->method('getResult')->willReturn($providedEvents);
        $this->em->expects($this->once())->method('createQueryBuilder')->willReturn($this->qb);
        $this->qb->expects($this->once())->method('select')->willReturn($this->qb);
        $this->qb->expects($this->once())->method('from')->willReturn($this->qb);
        $this->qb->expects($this->once())->method('andWhere')->willReturn($this->qb);
        $this->qb->expects($this->once())->method('getQuery')->willReturn($query);
        $this->qb->expects($this->atLeastOnce())->method('expr')->willReturn($expr);
        $expr->expects($this->once())->method('orX');
        $expr->expects($this->exactly(4))->method('andX');

        $events = $this->repo->getEvents(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));
        $this->assertSame($providedEvents, $events);
    }
}
