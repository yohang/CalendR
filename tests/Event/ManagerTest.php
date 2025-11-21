<?php

namespace CalendR\Test\Event;

use CalendR\Event\Collection\Basic as BasicCollection;
use CalendR\Event\Collection\Indexed;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Event\Manager;
use CalendR\Event\Event;
use CalendR\Period\Day;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Event\Provider\Basic;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Test class for Manager.
 */
class ManagerTest extends TestCase
{
    use ProphecyTrait;

    protected Manager $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $basic1       = new Basic;
        $basic2       = new Basic;
        $this->object = new Manager(['basic-1' => $basic1, 'basic-2' => $basic2]);

        $basic1->add(new Event('event-1', new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-03')));
        $basic2->add(new Event('event-2', new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-01-05')));
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

    public function testCollectionInstatiator(): void
    {
        $this->assertInstanceOf(
            BasicCollection::class,
            $this->object->find(new Month(new \DateTimeImmutable('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal()))
        );

        $this->object->setCollectionInstantiator(fn(): Indexed => new Indexed);

        $this->assertInstanceOf(
            Indexed::class,
            $this->object->find(new Month(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal()))
        );
    }

    public function testFindWithoutProvider(): void
    {
        $this->expectException(NoProviderFound::class);

        $manager = new Manager;
        $manager->find(new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(FactoryInterface::class)->reveal()));
    }
}
