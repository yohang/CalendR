<?php

declare(strict_types=1);

namespace CalendR\Period;

/**
 * @template TKey of int|string
 * @template TValue of PeriodInterface
 *
 * @extends \Traversable<TKey, TValue>
 */
interface IterablePeriod extends \Traversable
{
}
