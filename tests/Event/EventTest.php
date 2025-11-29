<?php

declare(strict_types=1);

namespace CalendR\Test\Event;

use CalendR\Event\Event;
use CalendR\Event\Exception\InvalidEvent;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    public function testItValidatesInput(): void
    {
        $this->expectException(InvalidEvent::class);
        $this->expectExceptionMessage('Events usually start before they end');

        new Event(new \DateTimeImmutable('now'), new \DateTimeImmutable('-1 day'));
    }

    public function testItCreatesEvent(): void
    {
        $start = new \DateTimeImmutable('2024-01-01 10:00:00');
        $end = new \DateTimeImmutable('2024-01-01 12:00:00');

        $event = new Event($start, $end);

        $this->assertInstanceOf(Event::class, $event);
    }

    #[DataProvider('providePeriods')]
    public function testItContainsPeriod(
        string $begin,
        string $end,
        PeriodInterface $period,
        bool $expected,
    ): void {
        $event = new Event(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));

        $this->assertSame($expected, $event->containsPeriod($period));
    }

    public static function providePeriods(): iterable
    {
        yield ['begin' => '2024-01-01 09:00:00', 'end' => '2024-01-01 11:00:00', 'period' => new Day(new \DateTimeImmutable('2025-11-29 00:00:00')), 'expected' => false];
        yield ['begin' => '2025-11-28 09:00:00', 'end' => '2025-11-30 11:00:00', 'period' => new Day(new \DateTimeImmutable('2025-11-29 00:00:00')), 'expected' => true];
        yield ['begin' => '2025-11-29 00:00:00', 'end' => '2025-11-30 00:00:00', 'period' => new Day(new \DateTimeImmutable('2025-11-29 00:00:00')), 'expected' => true];
    }

    #[DataProvider('provideDates')]
    public function testItContains(
        string $begin,
        string $end,
        \DateTimeImmutable $date,
        bool $expected,
    ): void {
        $event = new Event(new \DateTimeImmutable($begin), new \DateTimeImmutable($end));

        $this->assertSame($expected, $event->contains($date));
    }

    public static function provideDates(): iterable
    {
        yield ['begin' => '2024-01-01 09:00:00', 'end' => '2024-01-01 11:00:00', 'date' => new \DateTimeImmutable('2025-11-29 00:00:00'), 'expected' => false];
        yield ['begin' => '2025-11-28 09:00:00', 'end' => '2025-11-30 11:00:00', 'date' => new \DateTimeImmutable('2025-11-29 00:00:00'), 'expected' => true];
        yield ['begin' => '2025-11-29 00:00:00', 'end' => '2025-11-30 00:00:00', 'date' => new \DateTimeImmutable('2025-11-29 00:00:00'), 'expected' => true];
        yield ['begin' => '2025-11-29 00:00:00', 'end' => '2025-11-30 00:00:00', 'date' => new \DateTimeImmutable('2025-11-30 00:00:00'), 'expected' => false];
    }
}
