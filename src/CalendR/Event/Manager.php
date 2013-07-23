<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event;

use CalendR\Event\Exception\NoProviderFound;
use CalendR\Period\PeriodInterface,
    CalendR\Event\Provider\ProviderInterface;

/**
 * Manage events and providers
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Manager
{
    /**
     * @var ProviderInterface
     */
    protected $providers = array();

    /**
     * The callable used to instantiate the event collection
     *
     * @var callable
     */
    protected $collectionInstantiator;

    /**
     * @param array $providers
     * @param null  $instantiator
     */
    public function __construct(array $providers = array(), $instantiator = null)
    {
        $this->collectionInstantiator = $instantiator;
        if (null === $instantiator) {
            $this->collectionInstantiator = function() {
                return new Collection\Basic();
            };
        }

        foreach ($providers as $name => $provider) {
            $this->addProvider($name, $provider);
        }
    }

    /**
     * find events that matches the given period (during or over)
     *
     * @param \CalendR\Period\PeriodInterface $period
     * @param array                           $options
     *
     * @return array|EventInterface
     *
     * @throws NoProviderFound
     */
    public function find(PeriodInterface $period, array $options = array())
    {
        if (0 === count($this->providers)) {
            throw new NoProviderFound;
        }

        // Check if there's a provider option provided, used to filter the used providers
        $providers = isset($options['providers']) ? $options['providers'] : array();
        if (!is_array($providers)) {
            $providers = array($providers);
        }

        // Instantiate an event collection
        $collection = call_user_func($this->collectionInstantiator);
        foreach ($this->providers as $name => $provider) {
            if (count($providers) > 0 && !in_array($name, $providers)) {
                continue;
            }

            // Add matching events to the collection
            foreach ($provider->getEvents($period->getBegin(), $period->getEnd(), $options) as $event) {
                if ($period->containsEvent($event)) {
                    $collection->add($event);
                }
            }
        }

        return $collection;
    }

    /**
     * Adds a provider to the provider stack
     *
     * @param $name
     * @param ProviderInterface $provider
     */
    public function addProvider($name, ProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
    }

    /**
     * Sets the callable used to instantiate the event collection
     *
     * @param callable $collectionInstantiator
     */
    public function setCollectionInstantiator($collectionInstantiator)
    {
        $this->collectionInstantiator = $collectionInstantiator;
    }

    /**
     * @return \CalendR\Event\Provider\ProviderInterface
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
