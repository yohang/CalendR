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
    protected $providers;

    public function __construct(array $providers = array())
    {
        foreach ($providers as $name => $provider) {
            $this->addProvider($name, $provider);
        }
    }

    /**
     * find events that matches the given period (during or over)
     *
     * @param \CalendR\Period\PeriodInterface $period
     * @return array|EventInterface
     */
    public function find(PeriodInterface $period, array $options = array())
    {
        $events = array();
        foreach ($this->providers as $name => $provider) {
            if (isset($options['providers']) && !in_array($name, (array)$options['providers'])) {
                continue;
            }

            foreach ($provider->getEvents($period->getBegin(), $period->getEnd(), $options) as $event) {
                if ($event->containsPeriod($period)
                    || $event->isDuring($period)
                    || $period->contains($event->getBegin())
                    || $period->contains($event->getEnd())
                ) {
                    $events[] = $event;
                }
            }
        }

        return new Collection\Indexed($events);
    }

    public function addProvider($name, ProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
    }
}
