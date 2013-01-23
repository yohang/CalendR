<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event\Provider;

/**
 * This class provide multiple event providers support
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Aggregate implements ProviderInterface
{
    /**
     * @var array<ProviderInterface>
     */
    private $providers;

    /**
     * @param array<ProviderInterface> $providers
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

    /**
     * Adds a provider
     *
     * @param ProviderInterface $provider
     *
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
     * @param array     $options
     *
     * @return array<EventInterface>
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $events = array();

        foreach ($this->providers as $provider) {
            $events = array_merge($events, $provider->getEvents($begin, $end, $options));
        }

        return $events;
    }
}
