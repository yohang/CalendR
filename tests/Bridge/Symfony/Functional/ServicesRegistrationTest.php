<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Functional;

use CalendR\Calendar;
use CalendR\Event\EventManager;
use CalendR\Period\PeriodFactory;

final class ServicesRegistrationTest extends BaseTestCase
{
    public function testServicesAccessibles(): void
    {
        $this->assertInstanceOf(Calendar::class, self::getContainer()->get('calendr'));
        $this->assertInstanceOf(PeriodFactory::class, self::getContainer()->get('calendr.factory'));
        $this->assertInstanceOf(EventManager::class, self::getContainer()->get('calendr.event_manager'));
    }
}
