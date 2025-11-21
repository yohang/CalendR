<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Test\Bridge\Symfony\Bundle;

use Symfony\Component\DependencyInjection\ChildDefinition;
use CalendR\Bridge\Symfony\Bundle\CalendRBundle;
use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;
use CalendR\Event\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CalendRBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $hasAutoconfiguration = method_exists(new ContainerBuilder, 'registerForAutoconfiguration');

        $container = $this
            ->getMockBuilder(ContainerBuilder::class)
            ->onlyMethods(array_merge(['addCompilerPass'], $hasAutoconfiguration ? ['registerForAutoconfiguration']: []))
            ->getMock();

        $container->expects($this->once())->method('addCompilerPass')->with($this->isInstanceOf(EventProviderPass::class));

        if ($hasAutoconfiguration) {
            $providerChildDefinition = $this->getMockBuilder(ChildDefinition::class)->disableOriginalConstructor()->getMock();
            $providerChildDefinition->expects($this->once())->method('addTag')->with('calendr.event_provider');
            $container->expects($this->once())->method('registerForAutoconfiguration')->with(ProviderInterface::class)->willReturn($providerChildDefinition);
        }

        $bundle = new CalendRBundle;
        $bundle->build($container);

        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}
