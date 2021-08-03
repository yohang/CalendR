<?php

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

/**
 * Factory class for calendar handling.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Calendar
{
    private ?Manager $eventManager = null;

    protected ?FactoryInterface $factory = null;

    public function setEventManager(Manager $eventManager): void
    {
        $this->eventManager = $eventManager;
    }

    public function getEventManager(): Manager
    {
        if (null === $this->eventManager) {
            $this->eventManager = new Manager();
        }

        return $this->eventManager;
    }

    public function getYear($yearOrStart): Period\Year
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-01-01', $yearOrStart));
        }

        return $this->getFactory()->createYear($yearOrStart);
    }

    public function getMonth($yearOrStart, ?int $month = null): Month
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return $this->getFactory()->createMonth($yearOrStart);
    }

    public function getWeek($yearOrStart, ?int $week = null): Week
    {
        $factory = $this->getFactory();

        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, 0, STR_PAD_LEFT)));
        }

        return $factory->createWeek($factory->findFirstDayOfWeek($yearOrStart));
    }

    public function getDay($yearOrStart, ?int $month = null, ?int $day = null): Day
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return $this->getFactory()->createDay($yearOrStart);
    }

    public function getHour($yearOrStart, ?int $month = null, ?int $day = null, ?int $hour = null): Hour
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-%s-%s %s:00', $yearOrStart, $month, $day, $hour));
        }

        return $this->getFactory()->createHour($yearOrStart);
    }

    public function getMinute($yearOrStart, $month = null, $day = null, $hour = null, $minute = null): Minute
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(sprintf('%s-%s-%s %s:%s', $yearOrStart, $month, $day, $hour, $minute));
        }

        return $this->getFactory()->createMinute($yearOrStart);
    }

    public function getSecond($yearOrStart, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null, ?int $second = null): Second
    {
        if (!$yearOrStart instanceof \DateTimeInterface) {
            $yearOrStart = new \DateTimeImmutable(
                sprintf('%s-%s-%s %s:%s:%s', $yearOrStart, $month, $day, $hour, $minute, $second)
            );
        }

        return $this->getFactory()->createSecond($yearOrStart);
    }

    /**
     * @throws NoProviderFound
     */
    public function getEvents(PeriodInterface $period, array $options = []): CollectionInterface
    {
        return $this->getEventManager()->find($period, $options);
    }

    public function setFactory(FactoryInterface $factory): void
    {
        $this->factory = $factory;
    }

    public function getFactory(): FactoryInterface
    {
        if (null === $this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    /**
     * @param int $firstWeekday
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->getFactory()->setFirstWeekday($firstWeekday);
    }

    /**
     * @return int
     */
    public function getFirstWeekday()
    {
        return $this->factory->getFirstWeekday();
    }
}
