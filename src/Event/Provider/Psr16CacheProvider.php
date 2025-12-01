<?php

declare(strict_types=1);

namespace CalendR\Event\Provider;

use CalendR\Event\EventInterface;
use Psr\SimpleCache\CacheInterface;

final readonly class Psr16CacheProvider implements ProviderInterface
{
    public function __construct(
        protected CacheInterface $cache,
        protected ProviderInterface $provider,
        protected int $lifetime,
        protected ?string $namespace = null,
    ) {
    }

    #[\Override]
    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        $cacheKey = md5(serialize([$begin, $end, $options]));

        if (null !== $this->namespace) {
            $cacheKey = $this->namespace.'.'.$cacheKey;
        }

        if ($this->cache->has($cacheKey)) {
            /** @var list<EventInterface> $result */
            $result = $this->cache->get($cacheKey);

            return $result;
        }

        $events = $this->provider->getEvents($begin, $end, $options);
        $this->cache->set($cacheKey, $events, $this->lifetime);

        return $events;
    }
}
