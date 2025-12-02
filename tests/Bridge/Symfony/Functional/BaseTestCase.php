<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Functional;

use App\Entity\Event;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTestCase extends WebTestCase
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
        parent::setUp();

        self::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema([$entityManager->getClassMetadata(Event::class)]);
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
        parent::tearDown();

        restore_exception_handler();
    }
}
