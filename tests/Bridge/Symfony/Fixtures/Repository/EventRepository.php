<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use CalendR\Bridge\Doctrine\ORM\EventRepository as EventRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class EventRepository extends ServiceEntityRepository
{
    use EventRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getBeginFieldName(): string
    {
        return 'evt.begin';
    }

    public function getEndFieldName(): string
    {
        return 'evt.end';
    }
}
