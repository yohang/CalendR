<?php

namespace CalendR\Extension\Silex\Provider;

use CalendR\Calendar;
use CalendR\Event\Manager;
use CalendR\Bridge\Twig\CalendRExtension;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex CalendR Service Provider.
 *
 * @deprecated since 2.1, will be removed in 3.0 as silex isn't maintained anymore
 *
 * @author Yohan Giarelli<yohan@giarel.li>
 */
class CalendRServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        @trigger_error('The silex service provider is deprecated since 2.1 as Silex isn’t maintained anymore', E_USER_DEPRECATED);

        $app['calendr'] = $app->share(function ($app) {
            $calendr = new Calendar();
            $calendr->setEventManager($app['calendr.event_manager']);

            return $calendr;
        });

        $app['calendr.event_manager'] = $app->share(function ($app) {
            return new Manager(
                isset($app['calendr.event.providers']) ? $app['calendr.event.providers'] : array(),
                isset($app['calendr.event.collection.instantiator']) ? $app['calendr.event.collection.instantiator'] : null
            );
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        if (class_exists('Twig_Environment')) {
            $extension = new CalendRExtension($app['calendr']);
            if (isset($app['calendr.twig']) && $app['calendr.twig'] instanceof \Twig_Environment) {
                $app['calendr.twig']->addExtension($extension);
            } elseif (isset($app['twig']) && $app['twig'] instanceof \Twig_Environment) {
                $app['twig']->addExtension($extension);
            }
        }
    }
}
