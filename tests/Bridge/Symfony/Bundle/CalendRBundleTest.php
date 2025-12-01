<?php

declare(strict_types=1);

namespace CalendR\Test\Bridge\Symfony\Bundle;

use CalendR\Bridge\Symfony\Bundle\CalendRBundle;
use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;
use CalendR\Event\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CalendRBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $hasAutoconfiguration = method_exists(new ContainerBuilder(), 'registerForAutoconfiguration');

        $container = $this
            ->getMockBuilder(ContainerBuilder::class)
            ->onlyMethods(array_merge(['addCompilerPass'], $hasAutoconfiguration ? ['registerForAutoconfiguration'] : []))
            ->getMock();

        $container->expects($this->once())->method('addCompilerPass')->with($this->isInstanceOf(EventProviderPass::class));

        if ($hasAutoconfiguration) {
            $providerChildDefinition = $this->createMock(ChildDefinition::class);
            $providerChildDefinition->expects($this->once())->method('addTag')->with('calendr.event_provider');
            $container->expects($this->once())->method('registerForAutoconfiguration')->with(ProviderInterface::class)->willReturn($providerChildDefinition);
        }

        $bundle = new CalendRBundle();
        $bundle->build($container);

        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}
