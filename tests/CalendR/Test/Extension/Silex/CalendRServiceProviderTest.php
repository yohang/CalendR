<?php

namespace CalendR\Test\Extension\Silex;

use CalendR\Extension\Silex\Provider\CalendRServiceProvider;
use CalendR\Event\Provider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;

class CalendRServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CalendRServiceProvider
     */
    protected $provider;

    public function setUp()
    {
        $this->app = new Application();
        $this->provider = new CalendRServiceProvider();
    }

    public function testRegister()
    {
        $this->app->register($this->provider, array(
            'calendr.event.providers' => array(
                'basic' => new Provider\Basic
            )
        ));

        $this->assertTrue(isset($this->app['calendr']));
        $this->assertInstanceOf('CalendR\\Calendar', $this->app['calendr']);
        $this->assertTrue(isset($this->app['calendr.event_manager']));
        $this->assertInstanceOf('CalendR\\Event\\Manager', $this->app['calendr.event_manager']);
        $providers = $this->app['calendr.event_manager']->getProviders();
        $this->assertSame(1, count($providers));
        $this->assertInstanceOf('CalendR\\Event\\Provider\\Basic', $providers['basic']);
    }

    public function testBootWithoutTwig()
    {
        $this->app->register($this->provider);
        $this->app->boot();
        // Just expecting all is good
    }

    public function testBootWithTwig()
    {
        $this->app->register($this->provider);
        $this->app->register(new TwigServiceProvider());
        $this->app->boot();
        $this->assertInstanceOf('CalendR\\Extension\\Twig\\CalendRExtension', $this->app['twig']->getExtension('calendr'));
    }
}
