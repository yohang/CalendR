<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\Calendar;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Test\Fixtures\Period\Day as FixtureDay;
use CalendR\Test\Fixtures\Period\Hour as FixtureHour;
use CalendR\Test\Fixtures\Period\Minute as FixtureMinute;
use CalendR\Test\Fixtures\Period\Month as FixtureMonth;
use CalendR\Test\Fixtures\Period\Range as FixtureRange;
use CalendR\Test\Fixtures\Period\Second as FixtureSecond;
use CalendR\Test\Fixtures\Period\Week as FixtureWeek;
use CalendR\Test\Fixtures\Period\Year as FixtureYear;
use PHPUnit\Framework\TestCase;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\Week;
use CalendR\Period\Year;
use CalendR\Period\Range;

final class FactoryTest extends TestCase
{
    public function testCreateSecond(): void
    {
        $this->assertInstanceOf(
            Second::class,
            $this->getDefaultOptionsFactory()->createSecond(new \DateTimeImmutable('2012-01-01 17:23:49')),
        );
    }

    public function testCreateMinute(): void
    {
        $this->assertInstanceOf(
            Minute::class,
            $this->getDefaultOptionsFactory()->createMinute(new \DateTimeImmutable('2012-01-01 17:23')),
        );
    }

    public function testCreateHour(): void
    {
        $this->assertInstanceOf(
            Hour::class,
            $this->getDefaultOptionsFactory()->createHour(new \DateTimeImmutable('2012-01-01 17:00')),
        );
    }

    public function testCreateDay(): void
    {
        $this->assertInstanceOf(
            Day::class,
            $this->getDefaultOptionsFactory()->createDay(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateWeek(): void
    {
        $this->assertInstanceOf(
            Week::class,
            $this->getDefaultOptionsFactory()->createWeek(new \DateTimeImmutable('2012-W01')),
        );
    }

    public function testCreateMonth(): void
    {
        $this->assertInstanceOf(
            Month::class,
            $this->getDefaultOptionsFactory()->createMonth(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateYear(): void
    {
        $this->assertInstanceOf(
            Year::class,
            $this->getDefaultOptionsFactory()->createYear(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateRange(): void
    {
        $this->assertInstanceOf(
            Range::class,
            $this->getDefaultOptionsFactory()->createRange(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-02')),
        );
    }

    /**
     * @dataProvider providerGetFirstMondayAndLastSunday
     */
    public function testFindFirstDayOfWeek(Month $month, string $firstDay): void
    {
        $this->assertSame(
            $firstDay,
            $this->getDefaultOptionsFactory()->findFirstDayOfWeek($month->getBegin())->format('Y-m-d'),
        );
    }

    public static function providerGetFirstMondayAndLastSunday(): \Iterator
    {
        $factory = new Calendar();
        yield [$factory->getMonth(2012, 1), '2011-12-26'];
        yield [$factory->getMonth(2012, 2), '2012-01-30'];
        yield [$factory->getMonth(2012, 3), '2012-02-27'];
        yield [$factory->getMonth(2012, 9), '2012-08-27'];
        yield [$factory->getMonth(2012, 10), '2012-10-01'];
        yield [$factory->getMonth(2012, 12), '2012-11-26'];
    }

    protected function getDefaultOptionsFactory(): FactoryInterface
    {
        return new Factory();
    }
}
