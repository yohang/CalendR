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

use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Period\PeriodInterface;
use CalendR\Event\Provider\ProviderInterface;

/**
 * Manage events and providers.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Manager
{
    /**
     * @var ProviderInterface[]
     */
    protected array $providers = [];

    /**
     * The callable used to instantiate the event collection.
     *
     * @var callable
     */
    protected $collectionInstantiator;

    /**
     * @param iterable<ProviderInterface> $providers
     * @param ?callable $instantiator
     */
    public function __construct(iterable $providers = [], ?callable $instantiator = null)
    {
        $this->collectionInstantiator = $instantiator;
        if (null === $instantiator) {
            $this->collectionInstantiator = static function () {
                return new Collection\Basic;
            };
        }

        foreach ($providers as $name => $provider) {
            $this->addProvider($name, $provider);
        }
    }

    /**
     * find events that matches the given period (during or over).
     *
     * @throws NoProviderFound
     */
    public function find(PeriodInterface $period, array $options = []): CollectionInterface
    {
        if (0 === count($this->providers)) {
            throw new NoProviderFound();
        }

        // Check if there's a provider option provided, used to filter the used providers
        $providers = $options['providers'] ?? [];
        if (!is_array($providers)) {
            $providers = [$providers];
        }

        // Instantiate an event collection
        $collection = call_user_func($this->collectionInstantiator);
        foreach ($this->providers as $name => $provider) {
            if (count($providers) > 0 && !in_array($name, $providers, true)) {
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
     * Adds a provider to the provider stack.
     */
    public function addProvider(string $name, ProviderInterface $provider): void
    {
        $this->providers[$name] = $provider;
    }

    /**
     * Sets the callable used to instantiate the event collection.
     */
    public function setCollectionInstantiator(callable $collectionInstantiator): void
    {
        $this->collectionInstantiator = $collectionInstantiator;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
}
