<?php

declare(strict_types=1);

namespace CalendR\Period;

use CalendR\Event\EventInterface;
use CalendR\Exception;
use CalendR\Period\Exception\NullFactory;

/**
 * An abstract class that represent a date period and provide some base helpers.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
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

        return
            $this->includes($period, true)
            || $period->includes($this, true)
            || $this->contains($period->getBegin())
            || $this->contains($period->getEnd())
        ;
    }

    #[\Override]
    public function containsEvent(EventInterface $event): bool
    {
        return
            $event->containsPeriod($this)
            || $event->isDuring($this)
            || $this->contains($event->getBegin())
            || ($this->contains($event->getEnd()) && $event->getEnd()->format('c') !== $this->begin->format('c'))
        ;
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
     * @throws Exception
     */
    #[\Override]
    public function getNext(): PeriodInterface
    {
        return new static($this->end, $this->factory);
    }

    /**
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
            throw new NullFactory();
        }

        return $this->factory;
    }

    protected function createInvalidException(): Exception
    {
        $class = 'CalendR\Period\Exception\NotA'.(new \ReflectionClass($this))->getShortName();
        $exception = new $class();
        \assert($exception instanceof Exception);

        return $exception;
    }
}
