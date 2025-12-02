<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Functional;

use App\Entity\Event;
use App\Repository\EventRepository;

final class EventRepositoryTest extends BaseTestCase
{
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
