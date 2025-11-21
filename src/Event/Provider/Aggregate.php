<?php

declare(strict_types=1);

namespace CalendR\Event\Provider;

class Aggregate implements ProviderInterface
{
    /**
     * @var ProviderInterface[]
     */
    private array $providers;

    /**
     * @param ProviderInterface[] $providers
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            if (!$provider instanceof ProviderInterface) {
                throw new \InvalidArgumentException('Providers must implement CalendR\\Event\\ProviderInterface');
            }

            $this->providers[] = $provider;
        }
    }

    public function add(ProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        $events = [];

        foreach ($this->providers as $provider) {
            $events = array_merge($events, $provider->getEvents($begin, $end, $options));
        }

        return $events;
    }
}
