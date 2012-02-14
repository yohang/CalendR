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
 * Wraps a provider and don't provides already provided events
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Cache implements ProviderInterface
{
    private $provider;

    private $periods = array();

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Return events that matches to $begin && $end
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     */
    public function getEvents(\DateTime $begin, \DateTime $end)
    {
        foreach ($this->periods as $period) {
            if ($period[0]->diff($begin)->invert == 0 && $period[1]->diff($end)->invert == 1) {
                return array();
            }
        }

        $this->periods[] = array(clone $begin, clone $end);

        return $this->provider->getEvents($begin, $end);
    }

    public function getProvider()
    {
        return $this->provider;
    }
}
