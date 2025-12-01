<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\Exception\NotADay;
use CalendR\Period\Hour;
use CalendR\Period\PeriodFactoryInterface;
use CalendR\Period\PeriodInterface;
use CalendR\Period\PeriodPeriodFactory;
use CalendR\Period\Range;
use CalendR\Period\Year;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class DayTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): \Iterator
    {
        yield [new \DateTimeImmutable('2014-12-10 17:30')];
        yield [new \DateTimeImmutable('2014-12-10 00:00:01')];
        yield [new \DateTime('2014-12-10 17:30')];
        yield [new \DateTime('2014-12-10 00:00:01')];
    }

    public static function providerConstructValid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-03')];
        yield [new \DateTimeImmutable('2011-12-10')];
        yield [new \DateTimeImmutable('2013-07-13 00:00:00')];
        yield [new \DateTime('2012-01-03')];
        yield [new \DateTime('2011-12-10')];
        yield [new \DateTime('2013-07-13 00:00:00')];
    }

    #[DataProvider('providerConstructInvalid')]
    public function testConstructInvalid(\DateTimeInterface $start): void
    {
        $this->expectException(NotADay::class);

        new Day($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());
    }

    #[DataProvider('providerConstructValid')]
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $day = new Day($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertInstanceOf(Day::class, $day);
    }

    public static function providerContains(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-02'), new \DateTimeImmutable('2012-01-02 00:01'), new \DateTimeImmutable('2012-01-03')];
        yield [new \DateTimeImmutable('2012-05-30'), new \DateTimeImmutable('2012-05-30 12:25'), new \DateTimeImmutable('2012-05-29')];
        yield [new \DateTimeImmutable('2012-09-09'), new \DateTimeImmutable('2012-09-09 23:59'), new \DateTimeImmutable('2011-09-09')];
        yield [new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-03')];
        yield [new \DateTime('2012-01-02'), new \DateTime('2012-01-02 00:01'), new \DateTime('2012-01-03')];
        yield [new \DateTime('2012-05-30'), new \DateTime('2012-05-30 12:25'), new \DateTime('2012-05-29')];
        yield [new \DateTime('2012-09-09'), new \DateTime('2012-09-09 23:59'), new \DateTime('2011-09-09')];
        yield [new \DateTime('2013-02-02'), new \DateTime('2013-02-02'), new \DateTime('2013-02-03')];
    }

    #[DataProvider('providerContains')]
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $day = new Day($start, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertTrue($day->contains($contain));
        $this->assertFalse($day->contains($notContain));
    }

    public function testGetNext(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-01'), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $this->assertSame('2012-01-02', $day->getNext()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $this->assertSame('2012-02-01', $day->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-01'), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $this->assertSame('2011-12-31', $day->getPrevious()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $this->assertSame('2012-01-30', $day->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(PeriodFactoryInterface::class)->reveal());

        foreach ($day->getDatePeriod() as $dateTime) {
            $this->assertSame('2012-01-31', $dateTime->format('Y-m-d'));
        }
    }

    public function testCurrentDay(): void
    {
        $currentDate = new \DateTimeImmutable();
        $otherDate = (clone $currentDate)->add(new \DateInterval('P5D'));

        $currentDay = new Day(new \DateTimeImmutable(date('Y-m-d')), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $otherDay = $currentDay->getNext();

        $this->assertTrue($currentDay->contains($currentDate));
        $this->assertFalse($currentDay->contains($otherDate));
        $this->assertFalse($otherDay->contains($currentDate));
    }

    public function testToString(): void
    {
        $day = new Day(new \DateTimeImmutable(date('Y-m-d')), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $this->assertSame($day->getBegin()->format('l'), (string) $day);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Day::isValid(new \DateTimeImmutable('2013-05-01')));
        $this->assertTrue(Day::isValid(new \DateTimeImmutable('2013-05-01 00:00')));
        $this->assertTrue(Day::isValid(new \DateTimeImmutable(date('Y-m-d 00:00'))));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable()));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable('2013-05-01 12:43')));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable('2013-05-01 00:00:01')));
    }

    #[DataProvider('includesDataProvider')]
    public function testIncludes(\DateTimeInterface $begin, PeriodInterface $period, ?bool $strict, bool $result): void
    {
        $day = new Day($begin, $this->prophesize(PeriodFactoryInterface::class)->reveal());

        if (null === $strict) {
            $this->assertSame($result, $day->includes($period));

            return;
        }

        $this->assertSame($result, $day->includes($period, $strict));
    }

    public function testFormat(): void
    {
        $day = new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertSame(date('Y-m-d'), $day->format('Y-m-d'));
    }

    public function testIsCurrent(): void
    {
        $currentDay = new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(PeriodFactoryInterface::class)->reveal());
        $otherDay = new Day(new \DateTimeImmutable('1988-11-12'), $this->prophesize(PeriodFactoryInterface::class)->reveal());

        $this->assertTrue($currentDay->isCurrent());
        $this->assertFalse($otherDay->isCurrent());
    }

    public static function includesDataProvider(): \Iterator
    {
        yield [new \DateTimeImmutable('2013-09-01'), new Year(new \DateTimeImmutable('2013-01-01')), true, false];
        yield [new \DateTimeImmutable('2013-09-01'), new Year(new \DateTimeImmutable('2013-01-01')), false, true];
        yield [new \DateTimeImmutable('2013-09-01'), new Day(new \DateTimeImmutable('2013-09-01')), true, true];
        yield [new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01')), true, false];
        yield [new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01')), false, true];
        yield [new \DateTime('2013-09-01'), new Day(new \DateTime('2013-09-01')), true, true];
        yield [new \DateTime('2013-09-01'), new Range(new \DateTime('2013-08-01'), new \DateTime('2013-10-01')), null, false];
        yield [new \DateTime('2013-09-01'), new Range(new \DateTime('2013-08-01'), new \DateTime('2013-10-01')), true, false];
        yield [new \DateTime('2013-09-01'), new Range(new \DateTime('2013-08-01'), new \DateTime('2013-10-01')), false, true];
        yield [new \DateTime('2013-10-01'), new Range(new \DateTime('2013-08-01'), new \DateTime('2013-10-01')), false, false];
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-01-15');
        $day = new Day($start, new PeriodPeriodFactory());

        $i = 0;

        foreach ($day as $hourKey => $hour) {
            $this->assertIsInt($hourKey);
            $this->assertSame((int) $hour->getBegin()->format('H'), $hourKey);
            $this->assertInstanceOf(Hour::class, $hour);
            $this->assertSame($start->format('Y-m-d H'), $hour->getBegin()->format('Y-m-d H'));
            $this->assertSame('00:00', $hour->getBegin()->format('i:s'));

            $start = $start->add(new \DateInterval('PT1H'));
            ++$i;
        }

        $this->assertSame(24, $i);
    }
}
