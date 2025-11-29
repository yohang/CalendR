<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Event\EventInterface;
use CalendR\Exception;

/**
 * An abstract class that represent a date period and provide some base helpers.
 */
abstract class PeriodAbstract implements PeriodInterface
{
    protected \DateTimeImmutable $begin;

    protected \DateTimeImmutable $end;

    /**
     * @throws Exception
     */
    public function __construct(\DateTimeInterface $begin, protected ?FactoryInterface $factory = null)
    {
        if (!static::isValid($begin)) {
            throw $this->createInvalidException();
        }

        $this->begin = \DateTimeImmutable::createFromInterface($begin);
        $this->end = $this->begin->add($this->getDateInterval());
    }

    #[\Override]
    public function contains(\DateTimeInterface $date): bool
    {
        return $this->begin <= $date && $date < $this->end;
    }

    #[\Override]
    public function equals(PeriodInterface $period): bool
    {
        return
            $period instanceof static
            && $this->begin->format('Y-m-d-H-i-s') === $period->getBegin()->format('Y-m-d-H-i-s');
    }

    #[\Override]
    public function includes(PeriodInterface $period, bool $strict = true): bool
    {
        if ($strict) {
            return $this->getBegin() <= $period->getBegin() && $this->getEnd() >= $period->getEnd();
        }

        return $this->getBegin() < $period->getEnd() && $this->getEnd() > $period->getBegin();
    }

    #[\Override]
    public function containsEvent(EventInterface $event): bool
    {
        return $this->getBegin() <= $event->getEnd() && $this->getEnd() > $event->getBegin();
    }

    #[\Override]
    public function format(string $format): string
    {
        return $this->begin->format($format);
    }

    #[\Override]
    public function isCurrent(): bool
    {
        return $this->contains(new \DateTimeImmutable());
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     *
     * @throws Exception
     */
    #[\Override]
    public function getNext(): PeriodInterface
    {
        return new static($this->end, $this->factory);
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     *
     * @throws Exception|\DateInvalidOperationException
     */
    #[\Override]
    public function getPrevious(): PeriodInterface
    {
        $start = $this->begin->sub(static::getDateInterval());

        return new static($start, $this->factory);
    }

    #[\Override]
    public function getBegin(): \DateTimeImmutable
    {
        return $this->begin;
    }

    #[\Override]
    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }

    protected function getFactory(): FactoryInterface
    {
        if (null === $this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    /**
     * @psalm-suppress InvalidStringClass
     */
    protected function createInvalidException(): Exception
    {
        $class = 'CalendR\Period\Exception\NotA'.(new \ReflectionClass($this))->getShortName();
        $exception = new $class();
        \assert($exception instanceof Exception);

        return $exception;
    }
}
