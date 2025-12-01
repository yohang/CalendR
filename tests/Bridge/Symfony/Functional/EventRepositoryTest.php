<?php

declare(strict_types=1);

namespace Bridge\Symfony\Functional;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EventRepositoryTest extends KernelTestCase
{
    private const EVENT_DATA = [
        ['2025-11-01', '2025-12-01'],
        ['2025-11-01', '2025-11-02'],
        ['2025-11-02', '2025-11-03'],
        ['2025-11-03', '2025-11-04'],
        ['2025-11-04', '2025-11-05'],
        ['2025-11-05', '2025-11-06'],
    ];

    protected function setUp(): void
    {
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema([$entityManager->getClassMetadata(Event::class)]);

        foreach (self::EVENT_DATA as [$start, $end]) {
            $event = new Event();
            $event->begin = new \DateTimeImmutable($start);
            $event->end = new \DateTimeImmutable($end);

            $entityManager->persist($event);
        }

        $entityManager->flush();
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
    }

    public function testItReturnsEvents(): void
    {
        /** @var EventRepository $eventRepository */
        $eventRepository = self::getContainer()->get('doctrine')->getRepository(Event::class);

        $this->assertCount(6, $eventRepository->getEvents(new \DateTimeImmutable('2024-11-01'), new \DateTimeImmutable('2026-12-01')));
        $this->assertCount(2, $eventRepository->getEvents(new \DateTimeImmutable('2024-11-01'), new \DateTimeImmutable('2025-11-02')));
        $this->assertCount(1, $eventRepository->getEvents(new \DateTimeImmutable('2025-11-25'), new \DateTimeImmutable('2025-12-31')));
        $this->assertCount(0, $eventRepository->getEvents(new \DateTimeImmutable('2026-11-25'), new \DateTimeImmutable('2026-12-31')));
    }
}
