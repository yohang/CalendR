<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\CalendRExtension;
use CalendR\Calendar;
use CalendR\Event\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CalendRExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $config    = [];
        $container = new ContainerBuilder();
        (new CalendRExtension())->load($config, $container);

        $this->assertTrue($container->hasDefinition(Calendar::class));
        $this->assertTrue($container->hasDefinition(Manager::class));

        $this->assertTrue($container->hasAlias('calendr'));
        $this->assertTrue($container->hasAlias('calendr.factory'));
        $this->assertTrue($container->hasAlias('calendr.event_manager'));
    }
}
