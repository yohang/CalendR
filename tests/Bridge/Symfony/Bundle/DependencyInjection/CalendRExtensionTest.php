<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\CalendRExtension;
use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use CalendR\Calendar;
use CalendR\DayOfWeek;
use CalendR\Event\EventManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class CalendRExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $config = [];
        $container = new ContainerBuilder();
        (new CalendRExtension())->load($config, $container);

        $this->assertTrue($container->hasDefinition(Calendar::class));
        $this->assertTrue($container->hasDefinition(EventManager::class));

        $this->assertTrue($container->hasAlias('calendr'));
        $this->assertTrue($container->hasAlias('calendr.factory'));
        $this->assertTrue($container->hasAlias('calendr.event_manager'));
    }

    public function testItLoads(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $definition = $this->createMock(Definition::class);

        $containerBuilder
            ->expects($this->once())
            ->method('getReflectionClass')
            ->with(Configuration::class)
            ->willReturn(new \ReflectionClass(Configuration::class));

        $containerBuilder
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Calendar::class)
            ->willReturn($definition);

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('setFirstWeekday', [DayOfWeek::FRIDAY]);

        $symfonyHasEnum = method_exists(EnumNodeDefinition::class, 'enumFqcn');

        $configs = ['calendr' => ['periods' => ['default_first_weekday' => $symfonyHasEnum ? DayOfWeek::FRIDAY : DayOfWeek::FRIDAY->value]]];

        $extension = new CalendRExtension();
        $extension->load($configs, $containerBuilder);
    }

    public function testItValidatesIfInvalidConfig(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder
            ->expects($this->once())
            ->method('getReflectionClass')
            ->with(Configuration::class)
            ->willReturn(new \ReflectionClass(Configuration::class));

        $configs = ['calendr' => ['periods' => ['default_first_weekday' => 34]]];

        $extension = new CalendRExtension();
        $extension->load($configs, $containerBuilder);
    }
}
