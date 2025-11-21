<?php

declare(strict_types=1);

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\DayOfWeek;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {

        $treeBuilder = new TreeBuilder('calendr');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('periods')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_first_weekday')
                            ->defaultValue(DayOfWeek::MONDAY)
                            ->validate()
                                ->ifNotInArray(DayOfWeek::cases())
                                ->thenInvalid('Day must be be between 0 (Sunday) and 6 (Saturday)')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
