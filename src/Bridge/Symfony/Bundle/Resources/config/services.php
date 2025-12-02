<?php

declare(strict_types=1);

use CalendR\Bridge\Twig\CalendRExtension;
use CalendR\Calendar;
use CalendR\Event\EventManager;
use CalendR\Period\PeriodFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(EventManager::class)
            ->public();

    $container->services()
        ->set(PeriodFactory::class)
            ->public();

    $container->services()
        ->set(Calendar::class)
            ->arg('$factory', service(PeriodFactory::class))
            ->arg('$eventManager', service(EventManager::class))
            ->public();

    $container->services()
        ->set(CalendRExtension::class)
        ->arg('$factory', service(Calendar::class))
        ->tag('twig.extension');

    $container->services()
        ->alias('calendr', Calendar::class)
            ->public();

    $container->services()
        ->alias('calendr.factory', PeriodFactory::class)
            ->public();

    $container->services()
        ->alias('calendr.event_manager', EventManager::class)
            ->public();
};
