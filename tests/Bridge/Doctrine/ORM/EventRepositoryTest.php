<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Doctrine\ORM;

use CalendR\Event\Event;
use CalendR\Test\Stubs\EventRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EventRepositoryTest extends TestCase
{
    protected EventRepository $repo;

    protected MockObject $em;

    protected MockObject $qb;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $classMetadata = $this->getMockBuilder(ClassMetadata::class)->setConstructorArgs(['Event'])->getMock();
        $this->qb = $this->createMock(QueryBuilder::class);

        $this->repo = new EventRepository($this->em, $classMetadata);
    }

    public static function getEventsProvider(): \Iterator
    {
        yield [
            '2012-01-01',
            '2012-01-05',
            [
                new Event(new \DateTime('2011-12-25'), new \DateTime('2012-01-01'), 'event_during_begin'),
                new Event(new \DateTime('2012-01-04'), new \DateTime('2012-01-06'), 'event_during_end'),
                new Event(new \DateTime('2012-01-03'), new \DateTime('2012-01-04'), 'event_during_period'),
                new Event(new \DateTime('2011-12-25'), new \DateTime('2012-01-06'), 'event_around_period'),
            ],
        ];
    }

    #[DataProvider('getEventsProvider')]
    public function testGetEvents(string $begin, string $end, array $providedEvents): void
    {
        $expr = $this->createMock(Expr::class);
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
        $this->qb->expects($this->exactly(2))->method('setParameter')->willReturn($this->qb);
        $this->qb->expects($this->atLeastOnce())->method('expr')->willReturn($expr);
        $expr->expects($this->once())->method('andX');

        $events = $this->repo->getEvents(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));
        $this->assertSame($providedEvents, $events);
    }

    #[DataProvider('getEventsProvider')]
    public function testGetEventsQueryBuilder(string $begin, string $end): void
    {
        $expr = $this->createMock(Expr::class);
        $this->em->expects($this->exactly(3))->method('createQueryBuilder')->willReturn($this->qb);
        $this->qb->expects($this->exactly(3))->method('select')->willReturn($this->qb);
        $this->qb->expects($this->exactly(3))->method('from')->willReturn($this->qb);
        $this->qb->expects($this->exactly(2))->method('andWhere')->willReturn($this->qb);
        $this->qb->expects($this->exactly(4))->method('setParameter')->willReturn($this->qb);
        $this->qb->expects($this->atLeastOnce())->method('expr')->willReturn($expr);

        if ((new \ReflectionClass(Query::class))->isFinal()) {
            $this->qb->expects($this->exactly(1))->method('getQuery')->willReturn($this->createMock(AbstractQuery::class));
        } else {
            $this->qb->expects($this->exactly(1))->method('getQuery')->willReturn($this->createMock(Query::class));
        }

        $this->repo->createQueryBuilderForGetEvent([]);
        $this->repo->getEventsQueryBuilder(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));
        $this->repo->getEventsQuery(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));
    }
}
