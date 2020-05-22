<?php

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Period\Day;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('calendr');
        $root    = method_exists(TreeBuilder::class, 'getRootNode') ? $builder->getRootNode() : $builder->root('monolog');

        $root
            ->children()
                ->arrayNode('periods')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_first_weekday')
                            ->defaultValue(Day::MONDAY)
                            ->validate()
                                ->ifNotInArray(range(DAY::SUNDAY, DAY::SATURDAY))
                                ->thenInvalid('Day must be be between 0 (Sunday) and 6 (Saturday)')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
