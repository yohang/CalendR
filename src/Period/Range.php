<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Period\Exception\NotImplemented;

final class Range extends PeriodAbstract
{
    public function __construct(\DateTimeInterface $begin, \DateTimeInterface $end, ?FactoryInterface $factory = null)
    {
        $this->factory = $factory;
        $this->begin = \DateTimeImmutable::createFromInterface($begin);
        $this->end = \DateTimeImmutable::createFromInterface($end);
    }

    #[\Override]
    public static function isValid(\DateTimeInterface $start): bool
    {
        return true;
    }

    #[\Override]
    public function getNext(): self
    {
        $diff = $this->begin->diff($this->end);
        $begin = $this->begin->add($diff);
        $end = $this->end->add($diff);

        return new self($begin, $end, $this->factory);
    }

    #[\Override]
    public function getPrevious(): self
    {
        $diff = $this->begin->diff($this->end);
        $begin = $this->begin->sub($diff);
        $end = $this->end->sub($diff);

        return new self($begin, $end, $this->factory);
    }

    #[\Override]
    public function getDatePeriod(): \DatePeriod
    {
        return new \DatePeriod($this->begin, $this->begin->diff($this->end), $this->end);
    }

    /**
     * @throws NotImplemented
     */
    #[\Override]
    public static function getDateInterval(): \DateInterval
    {
        throw new NotImplemented('Range period doesn\'t support getDateInterval().');
    }
}
