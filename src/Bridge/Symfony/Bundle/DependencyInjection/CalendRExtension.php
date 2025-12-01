<?php

declare(strict_types=1);

namespace CalendR\Bridge\Symfony\Bundle\DependencyInjection;

use CalendR\Calendar;
use CalendR\DayOfWeek;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class CalendRExtension extends Extension
{
    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        \assert(null !== $configuration);

        /** @var array{periods: array{default_first_weekday: DayOfWeek|int}} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        $defaultFirstWeekday = $config['periods']['default_first_weekday'];
        if (!($defaultFirstWeekday instanceof DayOfWeek)) {
            $defaultFirstWeekday = DayOfWeek::from($defaultFirstWeekday);
        }

        $container
            ->getDefinition(Calendar::class)
            ->addMethodCall('setFirstWeekday', [$defaultFirstWeekday]);
    }
}
