<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\DayOfWeek;
use CalendR\Event\Event;
use CalendR\Period\Day;
use CalendR\Period\Exception\NotAMonth;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Period\Week;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class MonthTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-03')];
        yield [new \DateTimeImmutable('2014-12-10')];
    }

    public static function providerConstructValid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-01')];
        yield [new \DateTimeImmutable('2011-01-01')];
        yield [new \DateTimeImmutable('2013-04-01')];
    }

    public static function providerContains(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-02-09')];
        yield [new \DateTimeImmutable('2011-02-01'), new \DateTimeImmutable('2011-02-09'), new \DateTimeImmutable('2012-03-19')];
        yield [new \DateTimeImmutable('2012-09-01'), new \DateTimeImmutable('2012-09-09'), new \DateTimeImmutable('2011-09-01')];
        yield [new \DateTimeImmutable('2013-09-01'), new \DateTimeImmutable('2013-09-01'), new \DateTimeImmutable('2013-10-01')];
        yield [new \DateTime('2012-01-01'), new \DateTime('2012-01-04'), new \DateTime('2012-02-09')];
        yield [new \DateTime('2011-02-01'), new \DateTime('2011-02-09'), new \DateTime('2012-03-19')];
        yield [new \DateTime('2012-09-01'), new \DateTime('2012-09-09'), new \DateTime('2011-09-01')];
        yield [new \DateTime('2013-09-01'), new \DateTime('2013-09-01'), new \DateTime('2013-10-01')];
    }

    public static function providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek(): \Iterator
    {
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::MONDAY)), '2013-04-29', '2013-06-02'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::TUESDAY)), '2013-04-30', '2013-06-03'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::WEDNESDAY)), '2013-05-01', '2013-06-04'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::THURSDAY)), '2013-04-25', '2013-06-05'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::FRIDAY)), '2013-04-26', '2013-06-06'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::SATURDAY)), '2013-04-27', '2013-05-31'];
        yield [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(DayOfWeek::SUNDAY)), '2013-04-28', '2013-06-01'];
        yield [new Month(new \DateTimeImmutable('2013-09-01'), new Factory(DayOfWeek::SUNDAY)), '2013-09-01', '2013-10-05'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::MONDAY)), '2013-04-29', '2013-06-02'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::TUESDAY)), '2013-04-30', '2013-06-03'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::WEDNESDAY)), '2013-05-01', '2013-06-04'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::THURSDAY)), '2013-04-25', '2013-06-05'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::FRIDAY)), '2013-04-26', '2013-06-06'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::SATURDAY)), '2013-04-27', '2013-05-31'];
        yield [new Month(new \DateTime('2013-05-01'), new Factory(DayOfWeek::SUNDAY)), '2013-04-28', '2013-06-01'];
        yield [new Month(new \DateTime('2013-09-01'), new Factory(DayOfWeek::SUNDAY)), '2013-09-01', '2013-10-05'];
    }

    #[DataProvider('providerContains')]
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $month = new Month($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($month->contains($contain));
        $this->assertFalse($month->contains($notContain));
    }

    #[DataProvider('providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek')]
    public function testGetFirstDayOfFirstWeek(Month $month, string $firstDay): void
    {
        $this->assertSame($firstDay, $month->getFirstDayOfFirstWeek()->format('Y-m-d'));
    }

    #[DataProvider('providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek')]
    public function testGetLastDayOfLastWeek(Month $month, string $firstDay, string $lastDay): void
    {
        $this->assertSame($lastDay, $month->getLastDayOfLastWeek()->format('Y-m-d'));
    }

    #[DataProvider('providerConstructInvalid')]
    public function testConstructInvalid(\DateTimeImmutable $start): void
    {
        $this->expectException(NotAMonth::class);

        new Month($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    #[DataProvider('providerConstructValid')]
    public function testConstructValid(\DateTimeImmutable $start): void
    {
        $month = new Month($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Month::class, $month);
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-01-01');
        $month = new Month($start, new Factory());

        $i = 0;

        foreach ($month as $weekKey => $week) {
            $this->assertIsInt($weekKey);
            $this->assertSame((int) $week->getBegin()->format('W'), $weekKey);
            $this->assertInstanceOf(Week::class, $week);

            foreach ($week as $day) {
                if ($month->contains($day->getBegin())) {
                    $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));
                    $start = $start->add(new \DateInterval('P1D'));
                    ++$i;
                }
            }
        }

        $this->assertSame(31, $i);
    }

    public function testToString(): void
    {
        $date = new \DateTimeImmutable('2014-02-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertSame($date->format('F'), (string) $month);
    }

    public function testGetDays(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), new Factory());
        $days = $month->getDays();

        $this->assertCount(31, $days);

        $first = $days[0];
        foreach ($days as $day) {
            $this->assertTrue($first->equals($day));
            $first = $first->getNext();
        }
    }

    public function testAbstractEqualsChecksType(): void
    {
        $this->assertFalse((new Month(new \DateTimeImmutable('2025-11-01')))->equals(new Day(new \DateTimeImmutable('2025-11-01'))));
    }

    public function testGetExtendedMonth(): void
    {
        $month = new Month(new \DateTimeImmutable('2025-11-01'), new Factory(DayOfWeek::MONDAY));

        $extendedMonth = $month->getExtendedMonth();
        $this->assertSame('2025-10-27', $extendedMonth->getBegin()->format('Y-m-d'));
        $this->assertSame('2025-11-30', $extendedMonth->getEnd()->format('Y-m-d'));
    }

    public function testGetNext(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2012-02-01', $month->getNext()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTimeImmutable('2012-12-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2013-01-01', $month->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2011-12-01', $month->getPrevious()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTimeImmutable('2012-03-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2012-02-01', $month->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $date = new \DateTimeImmutable('2012-01-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($month->getDatePeriod() as $DateTimeImmutable) {
            $this->assertSame($date->format('Y-m-d'), $DateTimeImmutable->format('Y-m-d'));
            $date = $date->add(new \DateInterval('P1D'));
        }
    }

    public function testIsCurrent(): void
    {
        $currentDate = new \DateTimeImmutable();
        $otherDate = (clone $currentDate)->add(new \DateInterval('P5M'));

        $currentMonth = new Month(new \DateTimeImmutable(date('Y-m').'-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMonth = $currentMonth->getNext();

        $this->assertTrue($currentMonth->contains($currentDate));
        $this->assertFalse($currentMonth->contains($otherDate));
        $this->assertFalse($otherMonth->contains($currentDate));
    }

    #[DataProvider('providerItContainsEvent')]
    public function testItContainsEvent(
        string $month,
        \DateTimeImmutable $eventBegin,
        \DateTimeImmutable $eventEnd,
        bool $expected,
    ) {
        $month = new Month(new \DateTimeImmutable($month));
        $this->assertSame($expected, $month->containsEvent(new Event($eventBegin, $eventEnd)));
    }

    public static function providerItContainsEvent(): iterable
    {
        yield ['2025-11-01', new \DateTimeImmutable('2025-11-10'), new \DateTimeImmutable('2025-11-15'), true];
        yield ['2025-11-01', new \DateTimeImmutable('2025-10-28'), new \DateTimeImmutable('2025-11-02'), true];
        yield ['2025-11-01', new \DateTimeImmutable('2025-11-28'), new \DateTimeImmutable('2025-12-03'), true];
        yield ['2025-11-01', new \DateTimeImmutable('2025-10-20'), new \DateTimeImmutable('2025-10-25'), false];
        yield ['2025-11-01', new \DateTimeImmutable('2025-11-01'), new \DateTimeImmutable('2025-11-01'), true];
        yield ['2025-11-01', new \DateTimeImmutable('2025-12-01'), new \DateTimeImmutable('2025-12-01'), false];
    }
}
