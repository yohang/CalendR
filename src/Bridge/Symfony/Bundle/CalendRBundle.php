<?php

declare(strict_types=1);

namespace CalendR\Bridge\Symfony\Bundle;

use CalendR\Bridge\Symfony\Bundle\DependencyInjection\Compiler\EventProviderPass;
use CalendR\Event\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The Symfony Bundle
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class CalendRBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        if (method_exists($container, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(ProviderInterface::class)->addTag(EventProviderPass::TAG);
        }

        parent::build($container);

        $container->addCompilerPass(new EventProviderPass());
    }
}
