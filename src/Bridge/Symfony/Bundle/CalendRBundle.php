<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(ProviderInterface::class)->addTag(EventProviderPass::TAG);

        parent::build($container);

        $container->addCompilerPass(new EventProviderPass);
    }
}
