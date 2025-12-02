<?php

declare(strict_types=1);

use App\Controller\ShowCalendar;
use App\Repository\EventRepository;
use CalendR\Bridge\Symfony\Bundle\CalendRBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new TwigBundle(),
            new CalendRBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container): void
    {
        $container->setParameter('kernel.debug', true);
        $container->setParameter('kernel.secret', uniqid());

        $container->prependExtensionConfig('framework', ['test' => true, 'profiler' => true]);
        $container->prependExtensionConfig('twig', ['paths' => [__DIR__.'/templates']]);
        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'connections' => [
                    'default' => [
                        'url' => 'sqlite:///'.__DIR__.'/var/db.sqlite',
                    ],
                ],
            ],
            'orm' => [
                'mappings' => [
                    'App' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => __DIR__.'/../Entity',
                        'prefix' => 'App\Entity',
                    ],
                ],
            ],
        ]);

        $container
            ->setDefinition(LoggerInterface::class, new Definition(Logger::class))
            ->setArgument('$output', $this->getLogDir().'.log');

        $container->setAlias('logger', LoggerInterface::class);

        $container
            ->setDefinition(EventRepository::class, new Definition(EventRepository::class))
            ->setPublic(true)
            ->setAutoconfigured(true)
            ->setAutowired(true);

        $container
            ->setDefinition(ShowCalendar::class, new Definition(ShowCalendar::class))
            ->setPublic(true)
            ->setAutoconfigured(true)
            ->setAutowired(true);
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes
            ->add('show_calendar', '/calendar/{year}/{month}')
            ->controller(ShowCalendar::class)
            ->requirements(['year' => '\d{4}', 'month' => '\d{1,2}']);
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
