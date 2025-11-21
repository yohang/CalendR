<?php

declare(strict_types=1);

namespace CalendR\Event\Provider;

use Psr\SimpleCache\CacheInterface;

class Psr16CacheProvider implements ProviderInterface
{
    public function __construct(protected CacheInterface $cache, protected ProviderInterface $provider, protected int $lifetime, protected ?string $namespace = null)
    {
    }

    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        $cacheKey = md5(serialize([$begin, $end, $options]));

        if (null !== $this->namespace) {
            $cacheKey = $this->namespace.'.'.$cacheKey;
        }

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $events = $this->provider->getEvents($begin, $end, $options);
        $this->cache->set($cacheKey, $events, $this->lifetime);

        return $events;
    }
}
