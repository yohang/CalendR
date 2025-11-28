<?php

declare(strict_types=1);

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use CalendR\Event\Manager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventProviderPass implements CompilerPassInterface
{
    public const TAG = 'calendr.event_provider';

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $eventManager = $container->getDefinition(Manager::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $attributes) {
            $providerAlias = $attributes[0]['alias'] ?? $id;

            $eventManager->addMethodCall('addProvider', [$providerAlias, new Reference($id)]);
        }
    }
}
