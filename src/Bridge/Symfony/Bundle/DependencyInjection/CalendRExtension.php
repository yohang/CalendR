<?php

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Calendar;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CalendRExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container
            ->getDefinition(Calendar::class)
            ->addMethodCall('setFirstWeekday', [$config['periods']['default_first_weekday']]);
    }
}
