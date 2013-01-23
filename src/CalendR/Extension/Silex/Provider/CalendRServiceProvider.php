<?php

namespace CalendR\Extension\Silex\Provider;

use CalendR\Calendar;
use CalendR\Event\Manager;
use CalendR\Extension\Twig\CalendRExtension;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex CalendR Service Provider
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
        $app['calendr'] = $app->share(function($app) {
            $calendr = new Calendar();
            $calendr->setEventManager($app['calendr.event_manager']);

            return $calendr;
        });

        $app['calendr.event_manager'] = $app->share(function($app) {
            return new Manager(
                isset($app['calendr.event.providers']) ? $app['calendr.event.providers']: array(),
                isset($app['calendr.event.collection.instantiator']) ? $app['calendr.event.collection.instantiator']: null
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
            if (isset($app['calendr.twig']) && 'Twig_Environment' == get_class($app['calendr.twig'])) {
                $app['calendr.twig']->addExtension($extension);
            } elseif (isset($app['twig']) && 'Twig_Environment' == get_class($app['twig'])) {
                $app['twig']->addExtension($extension);
            }
        }
    }
}
