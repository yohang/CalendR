<?php

declare(strict_types=1);

namespace Bridge\Symfony\Functional;

use CalendR\Calendar;
use CalendR\Event\EventManager;
use CalendR\Period\PeriodFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ServicesRegistrationTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        restore_exception_handler();
    }

    public function testServicesAccessibles(): void
    {
        $this->assertInstanceOf(Calendar::class, self::getContainer()->get('calendr'));
        $this->assertInstanceOf(PeriodFactory::class, self::getContainer()->get('calendr.factory'));
        $this->assertInstanceOf(EventManager::class, self::getContainer()->get('calendr.event_manager'));
    }
}
