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

use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Cache the result of a provider
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Cache implements ProviderInterface
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param CacheInterface    $cache
     * @param ProviderInterface $provider
     * @param int               $lifetime
     * @param string            $namespace
     */
    public function __construct(CacheInterface $cache, ProviderInterface $provider, $lifetime, $namespace = null)
    {
        $this->cache     = $cache;
        $this->provider  = $provider;
        $this->lifetime  = $lifetime;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $cacheKey = md5(serialize(array($begin, $end, $options)));

        if (null !== $this->namespace) {
            $cacheKey = $this->namespace . '.' . $cacheKey;
        }

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $events = $this->provider->getEvents($begin, $end, $options);
        $this->cache->save($cacheKey, $events, $this->lifetime);

        return $events;
    }
}
