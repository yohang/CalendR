<?php

declare(strict_types=1);

namespace CalendR\Test\Event;

use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\Event;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Event\Manager;
use CalendR\Event\Provider\Basic;
use CalendR\Event\Provider\ProviderInterface;
use CalendR\Period\Day;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class ManagerTest extends TestCase
{
    use ProphecyTrait;

    protected Manager $object;

    protected function setUp(): void
    {
        $basic1 = new Basic();
        $this->object = new Manager(['basic-1' => $basic1]);
        $this->object->addProvider('basic-2', $basic2 = new Basic());

        $basic1->add(new Event(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-03'), 'event-1'));
        $basic2->add(new Event(new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-01-05'), 'event-2'));
    }

    public function testFind(): void
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        $this->assertCount(0, $this->object->find(new Day(new \DateTimeImmutable('00:00:00'), $factory)));
        $this->assertCount(1, $this->object->find(new Day(new \DateTimeImmutable('2012-01-01 00:00:00'), $factory)));
        $this->assertCount(1, $this->object->find(new Day(new \DateTimeImmutable('2012-01-04 00:00:00'), $factory)));

        $this->assertCount(2, $this->object->find(new Month(new \DateTimeImmutable('2012-01-01 00:00:00'), $factory)));

        $this->assertCount(1, $this->object->find(
            new Month(new \DateTimeImmutable('2012-01-01'), $factory),
            ['providers' => 'basic-1']
        ));
        $this->assertCount(1, $this->object->find(
            new Month(new \DateTimeImmutable('2012-01-01'), $factory),
            ['providers' => ['basic-2']]
        ));
        $this->assertCount(2, $this->object->find(
            new Month(new \DateTimeImmutable('2012-01-01'), $factory),
            ['providers' => ['basic-1', 'basic-2']]
        ));
        $this->assertCount(2, $this->object->find(
            new Month(new \DateTimeImmutable('2012-01-01'), $factory),
            ['providers' => []]
        ));
    }

    public function testFindWithoutProvider(): void
    {
        $this->expectException(NoProviderFound::class);

        $manager = new Manager();
        $manager->find(new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(FactoryInterface::class)->reveal()));
    }

    public function testCollectionInstantiator(): void
    {
        $provider = $this->createMock(ProviderInterface::class);
        $provider->method('getEvents')->willReturn([]);

        $collectionMock = $this->createMock(CollectionInterface::class);
        $manager = new Manager(
            ['provider' => $provider],
            collectionInstantiator: function () use ($collectionMock): CollectionInterface {
                return $collectionMock;
            },
        );

        $period = $this->createMock(PeriodInterface::class);
        $period->method('getBegin')->willReturn(new \DateTimeImmutable('2025-12-01'));
        $period->method('getEnd')->willReturn(new \DateTimeImmutable('2025-12-02'));

        $this->assertSame(
            $collectionMock,
            $manager->find($period),
        );
    }
}
