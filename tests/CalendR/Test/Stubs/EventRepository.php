<?php

namespace CalendR\Test\Stubs;

use Doctrine\ORM\EntityRepository;
use CalendR\Extension\Doctrine2\EventRepository as EventRepositoryTrait;

class EventRepository extends EntityRepository
{
    use EventRepositoryTrait;

}
