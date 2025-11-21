<?php

declare(strict_types=1);

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler;

use CalendR\Event\Manager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventProviderPass implements CompilerPassInterface
{
    public const TAG = 'calendr.event_provider';

    public function process(ContainerBuilder $container): void
    {
        $eventManager = $container->getDefinition(Manager::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $attributes) {
            $providerAlias = $attributes[0]['alias'] ?? $id;

            $eventManager->addMethodCall('addProvider', [$providerAlias, new Reference($id)]);
        }
    }
}
