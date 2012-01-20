<?php

namespace CalendR\Event;

/**
 * This class provide multiple event providers support
 */
class ProviderAggregate implements ProviderInterface
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
