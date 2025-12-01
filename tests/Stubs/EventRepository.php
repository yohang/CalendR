<?php

declare(strict_types=1);

namespace CalendR\Test\Stubs;

use CalendR\Bridge\Doctrine\ORM\EventRepository as EventRepositoryTrait;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    use EventRepositoryTrait;

    public function getBeginFieldName(): string
    {
        return 'evt.beginDate';
    }

    public function getEndFieldName(): string
    {
        return 'evt.endDate';
    }
}
