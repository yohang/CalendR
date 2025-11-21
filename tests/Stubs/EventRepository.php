<?php

declare(strict_types=1);

namespace CalendR\Test\Stubs;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    use \CalendR\Bridge\Doctrine\ORM\EventRepository;
}
