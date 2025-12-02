<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\PeriodFactory;
use CalendR\Period\PeriodFactoryInterface;
use CalendR\Period\Week;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class WeekTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructValid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-02')];
        yield [new \DateTimeImmutable('2012-01-09')];
        yield [new \DateTimeImmutable('2012-01-23')];
        yield [new \DateTime('2012-01-02')];
        yield [new \DateTime('2012-01-09')];
        yield [new \DateTime('2012-01-23')];
    }

    public static function providerContains(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-02'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-01-09')];
        yield [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-19')];
        yield [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2011-01-10')];
        yield [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-17')];
        yield [new \DateTime('2012-01-02'), new \DateTime('2012-01-04'), new \DateTime('2012-01-09')];
        yield [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-19')];
        yield [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2011-01-10')];
        yield [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-17')];
    }

    public static function providerNumber(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-02'), 1];
        yield [new \DateTimeImmutable('2012-01-09'), 2];
        yield [new \DateTimeImmutable('2011-12-26'), 52];
        yield [new \DateTime('2012-01-02'), 1];
        yield [new \DateTime('2012-01-09'), 2];
        yield [new \DateTime('2011-12-26'), 52];
    }

    #[DataProvider('providerContains')]
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $week = new Week($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertTrue($week->contains($contain));
        $this->assertFalse($week->contains($notContain));
    }

    #[DataProvider('providerNumber')]
    public function testNumber(\DateTimeInterface $start, int $number): void
    {
        $week = new Week($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertSame($week->getNumber(), $number);
    }

    #[DataProvider('providerConstructValid')]
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $week = new Week($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertInstanceOf(Week::class, $week);
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-W01');
        $week = new Week($start, new PeriodFactory());

        $i = 0;
        foreach ($week as $dayKey => $day) {
            $this->assertGreaterThan(0, preg_match('/^\\d{2}\\-\\d{2}\\-\\d{4}$/', (string) $dayKey));
            $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));

            $start = $start->add(new \DateInterval('P1D'));

            ++$i;
        }

        $this->assertSame(7, $i);
    }

    public function testIterationWithoutFactory(): void
    {
        $start = new \DateTimeImmutable('2012-W01');
        $week = new Week($start);

        foreach ($week as $day) {
            $this->assertInstanceOf(Day::class, $day);
        }
    }

    public function testGetDatePeriod(): void
    {
        $date = new \DateTimeImmutable('2012-01-01');
        $week = new Week($date, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        foreach ($week->getDatePeriod() as $dateTime) {
            $this->assertSame($date->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $date = $date->add(new \DateInterval('P1D'));
        }
    }

    public function testToString(): void
    {
        $date = new \DateTimeImmutable(date('Y-\\WW'));
        $week = new Week($date, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertSame($date->format('W'), (string) $week);
    }
}
