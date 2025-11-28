<?php

declare(strict_types=1);

namespace CalendR;

use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\Exception\NoProviderFound;
use CalendR\Event\Manager;
use CalendR\Period\Day;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Hour;
use CalendR\Period\Minute;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Second;
use CalendR\Period\Week;
use CalendR\Period\Year;

/**
 * Factory class for calendar handling.
 *
 * @api
 */
readonly class Calendar
{
    public function __construct(
        protected FactoryInterface $factory = new Factory(),
        private Manager $eventManager = new Manager(),
    ) {
    }

    public function getYear(\DateTimeInterface|int $yearOrStart): Year
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-01-01', $yearOrStart));
        }

        return $this->getFactory()->createYear($yearOrStart);
    }

    public function getMonth(\DateTimeInterface|int $yearOrStart, ?int $month = null): Month
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-%s-01', $yearOrStart, $month ?? ''));
        }

        return $this->getFactory()->createMonth($yearOrStart);
    }

    public function getWeek(\DateTimeInterface|int $yearOrStart, ?int $week = null): Week
    {
        $factory = $this->getFactory();

        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-W%s', $yearOrStart, str_pad((string) $week, 2, '0', \STR_PAD_LEFT)));
        }

        return $factory->createWeek($factory->findFirstDayOfWeek($yearOrStart));
    }

    public function getDay(\DateTimeInterface|int $yearOrStart, ?int $month = null, ?int $day = null): Day
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-%s-%s', $yearOrStart, $month ?? '', $day ?? ''));
        }

        return $this->getFactory()->createDay($yearOrStart);
    }

    public function getHour(\DateTimeInterface|int $yearOrStart, ?int $month = null, ?int $day = null, ?int $hour = null): Hour
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-%s-%s %s:00', $yearOrStart, $month ?? '', $day ?? '', $hour ?? ''));
        }

        return $this->getFactory()->createHour($yearOrStart);
    }

    public function getMinute(\DateTimeInterface|int $yearOrStart, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null): Minute
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(\sprintf('%s-%s-%s %s:%s', $yearOrStart, $month ?? '', $day ?? '', $hour ?? '', $minute ?? ''));
        }

        return $this->getFactory()->createMinute($yearOrStart);
    }

    public function getSecond(\DateTimeInterface|int $yearOrStart, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null, ?int $second = null): Second
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(
                \sprintf('%s-%s-%s %s:%s:%s', $yearOrStart, $month ?? '', $day ?? '', $hour ?? '', $minute ?? '', $second ?? '')
            );
        }

        return $this->getFactory()->createSecond($yearOrStart);
    }

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     *
     * @throws NoProviderFound
     */
    public function getEvents(PeriodInterface $period, array $options = []): CollectionInterface
    {
        return $this->getEventManager()->find($period, $options);
    }

    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    public function getEventManager(): Manager
    {
        return $this->eventManager;
    }

    public function setFirstWeekday(DayOfWeek $firstWeekday): void
    {
        $this->getFactory()->setFirstWeekday($firstWeekday);
    }

    public function getFirstWeekday(): DayOfWeek
    {
        return $this->factory->getFirstWeekday();
    }
}
