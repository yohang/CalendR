<?php

declare(strict_types=1);

namespace CalendR\Event\Provider;

use CalendR\Event\EventInterface;

/**
 * Base interface for event providers.
 */
interface ProviderInterface
{
    /**
     * Return events that matches to $begin && $end
     * $end date should be excluded.
     *
     * @return EventInterface[]
     */
    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array;
}
