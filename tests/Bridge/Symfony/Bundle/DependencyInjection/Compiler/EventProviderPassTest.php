<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;
use CalendR\Event\Manager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class EventProviderPassTest extends TestCase
{
    use ProphecyTrait;

    public function testProcess(): void
    {
        $eventManagerDefinition = $this->createMock(Definition::class);
        $containerBuilder = $this->getMockBuilder(ContainerBuilder::class)->onlyMethods(['findTaggedServiceIds', 'getDefinition'])->getMock();
        $containerBuilder->expects($this->once())->method('getDefinition')->with(Manager::class)->willReturn($eventManagerDefinition);
        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('calendr.event_provider')
            ->willReturn(
                [
                    'service1' => [[]],
                    'service2' => [['alias' => 'service2_alias']],
                ]
            );

        $eventManagerDefinition->expects($this->exactly(2))
                               ->method('addMethodCall');

        $pass = new EventProviderPass();
        $pass->process($containerBuilder);

        $this->assertInstanceOf(CompilerPassInterface::class, $pass);
    }
}
