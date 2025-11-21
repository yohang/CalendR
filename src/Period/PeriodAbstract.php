<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

use CalendR\Event\EventInterface;
use CalendR\Exception;

/**
 * An abstract class that represent a date period and provide some base helpers.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
abstract class PeriodAbstract implements PeriodInterface
{
    protected \DateTimeInterface $begin;

    protected \DateTimeInterface $end;

    /**
     * @throws Exception
     */
    public function __construct(\DateTimeInterface $begin, protected ?FactoryInterface $factory = null)
    {
        if (!static::isValid($begin)) {
            throw $this->createInvalidException();
        }

        $this->begin = clone $begin;
        $this->end   = (clone $begin)->add($this->getDateInterval());
    }

    public function contains(\DateTimeInterface $date): bool
    {
        return $this->begin <= $date && $date < $this->end;
    }

    public function equals(PeriodInterface $period): bool
    {
        return
            $period instanceof static &&
            $this->begin->format('Y-m-d-H-i-s') === $period->getBegin()->format('Y-m-d-H-i-s');
    }

    public function includes(PeriodInterface $period, bool $strict = true): bool
    {
        if ($strict) {
            return $this->getBegin() <= $period->getBegin() && $this->getEnd() >= $period->getEnd();
        }

        return
            $this->includes($period, true) ||
            $period->includes($this, true) ||
            $this->contains($period->getBegin()) ||
            $this->contains($period->getEnd())
        ;
    }

    public function containsEvent(EventInterface $event): bool
    {
        return
            $event->containsPeriod($this) ||
            $event->isDuring($this) ||
            $this->contains($event->getBegin()) ||
            ($event->getEnd() && $this->contains($event->getEnd())  && $event->getEnd()->format('c') !== $this->begin->format('c'))
        ;
    }

    public function format(string $format): string
    {
        return $this->begin->format($format);
    }

    public function isCurrent(): bool
    {
        return $this->contains(new \DateTimeImmutable());
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getNext(): PeriodInterface
    {
        return new static($this->end, $this->factory);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getPrevious(): PeriodInterface
    {
        $start = (clone $this->begin)->sub(static::getDateInterval());

        return new static($start, $this->factory);
    }

    public function getBegin(): \DateTimeInterface
    {
        return clone $this->begin;
    }

    public function getEnd(): \DateTimeInterface
    {
        return clone $this->end;
    }

    public function getFactory(): FactoryInterface
    {
        if (!$this->factory instanceof FactoryInterface) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    protected function createInvalidException(): Exception
    {
        $class = 'CalendR\Period\Exception\NotA' . (new \ReflectionClass($this))->getShortName();

        return new $class();
    }
}
