<?php

namespace CalendR\Event\Provider;

/**
 * This class provide multiple event providers support
 */
class Aggregate implements ProviderInterface
{
    /**
     * @var array|ProviderInterface
     */
    private $providers;

    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            if (!$provider instanceof ProviderInterface) {
                throw new \InvalidArgumentException('Providers must implement CalendR\\Event\\ProviderInterface');
            }
            $this->providers[] = $provider;
        }
    }

    /**
     * Adds a provider
     *
     * @param ProviderInterface $provider
     * @return Aggregate
     */
    public function add(ProviderInterface $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * Return events that matches to $begin && $end
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     * @return array|EventInterface
     */
    public function getEvents(\DateTime $begin, \DateTime $end)
    {
        $events = array();

        foreach ($this->providers as $provider) {
            $events = array_merge($events, $provider->getEvents($begin, $end));
        }

        return $events;
    }

}
