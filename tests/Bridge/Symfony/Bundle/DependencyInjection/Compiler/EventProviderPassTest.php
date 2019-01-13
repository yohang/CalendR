<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Test\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;

use CalendR\Event\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class EventProviderPassTest extends TestCase
{
    public function testProcess()
    {
        $eventManagerDefinition = $this->getMockBuilder(Definition::class)->getMock();
        $containerBuilder = $this->getMockBuilder(ContainerBuilder::class)->getMock();
        $containerBuilder->expects($this->once())->method('getDefinition')->with(Manager::class)->willReturn($eventManagerDefinition);
        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('calendr.event_provider')
            ->willReturn([
                'service1' => [[]],
                'service2' => [['alias' => 'service2_alias']]
            ]);

        $eventManagerDefinition->expects($this->at(0))->method('addMethodCall')->with('addProvider', ['service1', new Reference('service1')]);
        $eventManagerDefinition->expects($this->at(1))->method('addMethodCall')->with('addProvider', ['service2_alias', new Reference('service2')]);

        $pass = new EventProviderPass;
        $pass->process($containerBuilder);

        $this->assertInstanceOf(CompilerPassInterface::class, $pass);
    }
}
