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

        $enumNode = $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('periods')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('default_first_weekday');

        if (method_exists($enumNode, 'enumFqcn')) {
            $enumNode
                ->enumFqcn(DayOfWeek::class)
                ->defaultValue(DayOfWeek::MONDAY)
                ->validate()
                    ->ifNotInArray(DayOfWeek::cases())
                    ->thenInvalid('Day must be a case of '.DayOfWeek::class)
                ->end();
        } else {
            $enumNode
                ->values(array_map(fn (DayOfWeek $dayOfWeek) => $dayOfWeek->value, DayOfWeek::cases()))
                ->defaultValue(DayOfWeek::MONDAY->value)
                ->validate()
                    ->ifNotInArray(array_map(static fn (DayOfWeek $d) => $d->value, DayOfWeek::cases()))
                    ->thenInvalid('Day must be be between 0 (Sunday) and 6 (Saturday)')
                ->end();
        }

        return $treeBuilder;
    }
}
