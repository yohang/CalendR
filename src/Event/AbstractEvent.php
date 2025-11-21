<?php

declare(strict_types=1);

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event;

use CalendR\Period\PeriodInterface;

/**
 * Abstract class that ease event manipulation.
 *
 * @author Yohan Giareli <yohan@giarel.li>
 */
abstract class AbstractEvent implements EventInterface
{
    public function contains(\DateTimeInterface $datetime): bool
    {
        return $this->getBegin() <= $datetime && $datetime < $this->getEnd();
    }

    public function containsPeriod(PeriodInterface $period): bool
    {
        return $this->contains($period->getBegin()) && $this->contains($period->getEnd());
    }

    public function isDuring(PeriodInterface $period): bool
    {
        return $this->getBegin() >= $period->getBegin() && $this->getEnd() < $period->getEnd();
    }
}
