<?php

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
    const TAG = 'calendr.event_provider';

    public function process(ContainerBuilder $container)
    {
        $eventManager = $container->getDefinition(Manager::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $attributes) {
            $providerAlias = isset($attributes[0]) && isset($attributes[0]['alias']) ? $attributes[0]['alias'] : $id;
            $eventManager->addMethodCall('addProvider', [$providerAlias, new Reference($id)]);
        }
    }
}
