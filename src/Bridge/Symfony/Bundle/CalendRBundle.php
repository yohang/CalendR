<?php

declare(strict_types=1);

namespace CalendR\Bridge\Symfony\Bundle;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;
use CalendR\Event\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CalendRBundle extends Bundle
{
    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        if (method_exists($container, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(ProviderInterface::class)->addTag(EventProviderPass::TAG);
        }

        $container->addCompilerPass(new EventProviderPass());
    }
}
