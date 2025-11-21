<?php

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

/**
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class FactoryTest extends TestCase
{
    public function testCreateSecond(): void
    {
        $this->assertInstanceOf(
            Second::class,
            $this->getDefaultOptionsFactory()->createSecond(new \DateTimeImmutable('2012-01-01 17:23:49')),
        );

        $this->assertInstanceOf(
            FixtureSecond::class,
            $this->getAlternateOptionsFactory()->createSecond(new \DateTimeImmutable('2012-01-01 17:23:49')),
        );
    }

    public function testCreateMinute(): void
    {
        $this->assertInstanceOf(
            Minute::class,
            $this->getDefaultOptionsFactory()->createMinute(new \DateTimeImmutable('2012-01-01 17:23')),
        );

        $this->assertInstanceOf(
            FixtureMinute::class,
            $this->getAlternateOptionsFactory()->createMinute(new \DateTimeImmutable('2012-01-01 17:23')),
        );
    }

    public function testCreateHour(): void
    {
        $this->assertInstanceOf(
            Hour::class,
            $this->getDefaultOptionsFactory()->createHour(new \DateTimeImmutable('2012-01-01 17:00')),
        );

        $this->assertInstanceOf(
            FixtureHour::class,
            $this->getAlternateOptionsFactory()->createHour(new \DateTimeImmutable('2012-01-01 17:00')),
        );
    }

    public function testCreateDay(): void
    {
        $this->assertInstanceOf(
            Day::class,
            $this->getDefaultOptionsFactory()->createDay(new \DateTimeImmutable('2012-01-01')),
        );

        $this->assertInstanceOf(
            FixtureDay::class,
            $this->getAlternateOptionsFactory()->createDay(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateWeek(): void
    {
        $this->assertInstanceOf(
            Week::class,
            $this->getDefaultOptionsFactory()->createWeek(new \DateTimeImmutable('2012-W01')),
        );

        $this->assertInstanceOf(
            FixtureWeek::class,
            $this->getAlternateOptionsFactory()->createWeek(new \DateTimeImmutable('2012-W01')),
        );
    }

    public function testCreateMonth(): void
    {
        $this->assertInstanceOf(
            Month::class,
            $this->getDefaultOptionsFactory()->createMonth(new \DateTimeImmutable('2012-01-01')),
        );

        $this->assertInstanceOf(
            FixtureMonth::class,
            $this->getAlternateOptionsFactory()->createMonth(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateYear(): void
    {
        $this->assertInstanceOf(
            Year::class,
            $this->getDefaultOptionsFactory()->createYear(new \DateTimeImmutable('2012-01-01')),
        );

        $this->assertInstanceOf(
            FixtureYear::class,
            $this->getAlternateOptionsFactory()->createYear(new \DateTimeImmutable('2012-01-01')),
        );
    }

    public function testCreateRange(): void
    {
        $this->assertInstanceOf(
            Range::class,
            $this->getDefaultOptionsFactory()->createRange(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-02')),
        );

        $this->assertInstanceOf(
            FixtureRange::class,
            $this->getAlternateOptionsFactory()->createRange(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-02')),
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

    public static function providerGetFirstMondayAndLastSunday(): array
    {
        $factory = new Calendar();

        return [
            [$factory->getMonth(2012, 1), '2011-12-26'],
            [$factory->getMonth(2012, 2), '2012-01-30'],
            [$factory->getMonth(2012, 3), '2012-02-27'],
            [$factory->getMonth(2012, 9), '2012-08-27'],
            [$factory->getMonth(2012, 10), '2012-10-01'],
            [$factory->getMonth(2012, 12), '2012-11-26'],
        ];
    }

    protected function getDefaultOptionsFactory(): FactoryInterface
    {
        return new Factory;
    }

    protected function getAlternateOptionsFactory(): FactoryInterface
    {
        return new Factory(
            [
                'second_class' => FixtureSecond::class,
                'minute_class' => FixtureMinute::class,
                'hour_class'   => FixtureHour::class,
                'day_class'    => FixtureDay::class,
                'month_class'  => FixtureMonth::class,
                'range_class'  => FixtureRange::class,
                'week_class'   => FixtureWeek::class,
                'year_class'   => FixtureYear::class,
            ]
        );
    }
}
