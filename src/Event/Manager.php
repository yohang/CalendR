<?php

declare(strict_types=1);

namespace CalendR\Event;

use CalendR\Event\Collection\ArrayCollection;
use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Event\Provider\ProviderInterface;
use CalendR\Period\PeriodInterface;

/**
 * @api
 */
class Manager
{
    /**
     * @var array<string, ProviderInterface>
     */
    protected array $providers = [];

    /**
     * The callable used to instantiate the event collection.
     *
     * @var callable():CollectionInterface
     */
    protected $collectionInstantiator;

    /**
     * @param iterable<string, ProviderInterface> $providers
     * @param callable():CollectionInterface|null $collectionInstantiator
     */
    public function __construct(
        iterable $providers = [],
        ?callable $collectionInstantiator = null,
    ) {
        $this->collectionInstantiator = $collectionInstantiator ?? static fn (): ArrayCollection => new ArrayCollection();

        foreach ($providers as $name => $provider) {
            $this->addProvider($name, $provider);
        }
    }

    /**
     * find events that match the given period (during or over).
     *
     * @param array{providers?: list<string>|string} $options
     *
     * @throws NoProviderFound
     */
    public function find(PeriodInterface $period, array $options = []): CollectionInterface
    {
        if (0 === \count($this->providers)) {
            throw new NoProviderFound();
        }

        // Check if there's a provider option provided, used to filter the used providers
        $providers = $options['providers'] ?? [];
        if (!\is_array($providers)) {
            $providers = [$providers];
        }

        // Instantiate an event collection
        $collectionInstantiator = $this->collectionInstantiator;
        $collection = $collectionInstantiator();
        foreach ($this->providers as $name => $provider) {
            if (\count($providers) > 0 && !\in_array($name, $providers, true)) {
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

    public function addProvider(string $name, ProviderInterface $provider): void
    {
        $this->providers[$name] = $provider;
    }
}
