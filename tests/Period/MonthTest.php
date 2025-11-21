<?php

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\Exception\NotAMonth;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Period\Week;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MonthTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-03')],
            [new \DateTimeImmutable('2014-12-10')],
        ];
    }

    public static function providerConstructValid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-01')],
            [new \DateTimeImmutable('2011-01-01')],
            [new \DateTimeImmutable('2013-04-01')],
        ];
    }

    public static function providerContains(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-02-09')],
            [new \DateTimeImmutable('2011-02-01'), new \DateTimeImmutable('2011-02-09'), new \DateTimeImmutable('2012-03-19')],
            [new \DateTimeImmutable('2012-09-01'), new \DateTimeImmutable('2012-09-09'), new \DateTimeImmutable('2011-09-01')],
            [new \DateTimeImmutable('2013-09-01'), new \DateTimeImmutable('2013-09-01'), new \DateTimeImmutable('2013-10-01')],
            [new \DateTime('2012-01-01'), new \DateTime('2012-01-04'), new \DateTime('2012-02-09')],
            [new \DateTime('2011-02-01'), new \DateTime('2011-02-09'), new \DateTime('2012-03-19')],
            [new \DateTime('2012-09-01'), new \DateTime('2012-09-09'), new \DateTime('2011-09-01')],
            [new \DateTime('2013-09-01'), new \DateTime('2013-09-01'), new \DateTime('2013-10-01')],
        ];
    }

    public static function providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek(): array
    {
        return [
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::MONDAY])), '2013-04-29', '2013-06-02'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::TUESDAY])), '2013-04-30', '2013-06-03'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::WEDNESDAY])), '2013-05-01', '2013-06-04'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::THURSDAY])), '2013-04-25', '2013-06-05'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::FRIDAY])), '2013-04-26', '2013-06-06'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::SATURDAY])), '2013-04-27', '2013-05-31'],
            [new Month(new \DateTimeImmutable('2013-05-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-04-28', '2013-06-01'],
            [new Month(new \DateTimeImmutable('2013-09-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-09-01', '2013-10-05'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::MONDAY])), '2013-04-29', '2013-06-02'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::TUESDAY])), '2013-04-30', '2013-06-03'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::WEDNESDAY])), '2013-05-01', '2013-06-04'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::THURSDAY])), '2013-04-25', '2013-06-05'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::FRIDAY])), '2013-04-26', '2013-06-06'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::SATURDAY])), '2013-04-27', '2013-05-31'],
            [new Month(new \DateTime('2013-05-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-04-28', '2013-06-01'],
            [new Month(new \DateTime('2013-09-01'), new Factory(['first_weekday' => Day::SUNDAY])), '2013-09-01', '2013-10-05'],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $month = new Month($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($month->contains($contain));
        $this->assertFalse($month->contains($notContain));
    }

    /**
     * @dataProvider providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek
     */
    public function testGetFirstDayOfFirstWeek(Month $month, string $firstDay): void
    {
        $this->assertSame($firstDay, $month->getFirstDayOfFirstWeek()->format('Y-m-d'));
    }

    /**
     * @dataProvider providerGetFirstDayOfFirstWeekAndLastDayOfLastWeek
     */
    public function testGetLastDayOfLastWeek(Month $month, string $firstDay, string $lastDay): void
    {
        $this->assertSame($lastDay, $month->getLastDayOfLastWeek()->format('Y-m-d'));
    }

    /**
     * @dataProvider providerConstructInvalid
     */
    public function testConstructInvalid(\DateTimeImmutable $start): void
    {
        $this->expectException(NotAMonth::class);

        new Month($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid(\DateTimeImmutable $start): void
    {
        $month = new Month($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Month::class, $month);
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-01-01');
        $month = new Month($start, new Factory);

        $i = 0;

        foreach ($month as $weekKey => $week) {
            $this->assertTrue(is_numeric($weekKey) && $weekKey > 0 && $weekKey < 54);
            $this->assertInstanceOf(Week::class, $week);

            foreach ($week as $day) {
                if ($month->contains($day->getBegin())) {
                    $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));
                    $start = $start->add(new \DateInterval('P1D'));
                    $i++;
                }
            }
        }

        $this->assertEquals(31, $i);
    }

    public function testToString(): void
    {
        $date  = new \DateTimeImmutable('2014-02-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertSame($date->format('F'), (string)$month);
    }

    public function testGetDays(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), new Factory());
        $days  = $month->getDays();

        $this->assertCount(31, $days);

        $first = $days[0];
        foreach ($days as $day) {
            $this->assertTrue($first->equals($day));
            $first = $first->getNext();
        }
    }

    public function testGetNext(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-02-01', $month->getNext()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTimeImmutable('2012-12-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-01-01', $month->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $month = new Month(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-01', $month->getPrevious()->getBegin()->format('Y-m-d'));

        $month = new Month(new \DateTimeImmutable('2012-03-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-02-01', $month->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $date  = new \DateTimeImmutable('2012-01-01');
        $month = new Month($date, $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($month->getDatePeriod() as $DateTimeImmutable) {
            $this->assertEquals($date->format('Y-m-d'), $DateTimeImmutable->format('Y-m-d'));
            $date = $date->add(new \DateInterval('P1D'));
        }
    }

    public function testIsCurrent(): void
    {
        $currentDate = new \DateTimeImmutable();
        $otherDate   = (clone $currentDate)->add(new \DateInterval('P5M'));

        $currentMonth = new Month(new \DateTimeImmutable(date('Y-m') . '-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMonth   = $currentMonth->getNext();

        $this->assertTrue($currentMonth->contains($currentDate));
        $this->assertFalse($currentMonth->contains($otherDate));
        $this->assertFalse($otherMonth->contains($currentDate));
    }
}
