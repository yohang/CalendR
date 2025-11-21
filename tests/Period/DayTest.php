<?php

namespace CalendR\Test\Period;

use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\Exception\NotADay;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DayTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): array
    {
        return [
            [new \DateTimeImmutable('2014-12-10 17:30')],
            [new \DateTimeImmutable('2014-12-10 00:00:01')],
            [new \DateTime('2014-12-10 17:30')],
            [new \DateTime('2014-12-10 00:00:01')],
        ];
    }

    public static function providerConstructValid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-03')],
            [new \DateTimeImmutable('2011-12-10')],
            [new \DateTimeImmutable('2013-07-13 00:00:00')],
            [new \DateTime('2012-01-03')],
            [new \DateTime('2011-12-10')],
            [new \DateTime('2013-07-13 00:00:00')],
        ];
    }

    /**
     * @dataProvider providerConstructInvalid
     */
    public function testConstructInvalid(\DateTimeInterface $start): void
    {
        $this->expectException(NotADay::class);

        new Day($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $day = new Day($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Day::class, $day);
    }

    public static function providerContains(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-02'), new \DateTimeImmutable('2012-01-02 00:01'), new \DateTimeImmutable('2012-01-03')],
            [new \DateTimeImmutable('2012-05-30'), new \DateTimeImmutable('2012-05-30 12:25'), new \DateTimeImmutable('2012-05-29')],
            [new \DateTimeImmutable('2012-09-09'), new \DateTimeImmutable('2012-09-09 23:59'), new \DateTimeImmutable('2011-09-09')],
            [new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-03')],
            [new \DateTime('2012-01-02'), new \DateTime('2012-01-02 00:01'), new \DateTime('2012-01-03')],
            [new \DateTime('2012-05-30'), new \DateTime('2012-05-30 12:25'), new \DateTime('2012-05-29')],
            [new \DateTime('2012-09-09'), new \DateTime('2012-09-09 23:59'), new \DateTime('2011-09-09')],
            [new \DateTime('2013-02-02'), new \DateTime('2013-02-02'), new \DateTime('2013-02-03')],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $day = new Day($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($day->contains($contain));
        $this->assertFalse($day->contains($notContain));
    }

    public function testGetNext(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-02', $day->getNext()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-02-01', $day->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31', $day->getPrevious()->getBegin()->format('Y-m-d'));

        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-30', $day->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $day = new Day(new \DateTimeImmutable('2012-01-31'), $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($day->getDatePeriod() as $dateTime) {
            $this->assertEquals('2012-01-31', $dateTime->format('Y-m-d'));
        }
    }

    public function testCurrentDay(): void
    {
        $currentDate = new \DateTimeImmutable();
        $otherDate   = (clone $currentDate)->add(new \DateInterval('P5D'));

        $currentDay = new Day(new \DateTimeImmutable(date('Y-m-d')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherDay   = $currentDay->getNext();

        $this->assertTrue($currentDay->contains($currentDate));
        $this->assertFalse($currentDay->contains($otherDate));
        $this->assertFalse($otherDay->contains($currentDate));
    }

    public function testToString(): void
    {
        $day = new Day(new \DateTimeImmutable(date('Y-m-d')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($day->getBegin()->format('l'), (string)$day);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Day::isValid(new \DateTimeImmutable('2013-05-01')));
        $this->assertTrue(Day::isValid(new \DateTimeImmutable('2013-05-01 00:00')));
        $this->assertTrue(Day::isValid(new \DateTimeImmutable(date('Y-m-d 00:00'))));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable('2013-05-01 12:43')));
        $this->assertFalse(Day::isValid(new \DateTimeImmutable('2013-05-01 00:00:01')));
    }

    /**
     * @dataProvider includesDataProvider
     */
    public function testIncludes(\DateTimeInterface $begin, PeriodInterface $period, bool $strict, bool $result): void
    {
        $day = new Day($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $day->includes($period, $strict));
    }

    public function testFormat(): void
    {
        $day = new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertSame(date('Y-m-d'), $day->format('Y-m-d'));
    }

    public function testIsCurrent(): void
    {
        $currentDay = new Day(new \DateTimeImmutable('00:00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $otherDay   = new Day(new \DateTimeImmutable('1988-11-12'), $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($currentDay->isCurrent());
        $this->assertFalse($otherDay->isCurrent());
    }

    public function includesDataProvider(): array
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return [
            [new \DateTimeImmutable('2013-09-01'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), true, true],
            [new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01'), $factory), true, false],
            [new \DateTime('2013-09-01'), new Year(new \DateTime('2013-01-01'), $factory), false, true],
            [new \DateTime('2013-09-01'), new Day(new \DateTime('2013-09-01'), $factory), true, true],
        ];
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-01-15');
        $day   = new Day($start, new Factory());

        $i = 0;

        foreach ($day as $hourKey => $hour) {
            $this->assertTrue(is_int($hourKey) && $hourKey >= 0 && $hourKey < 24);
            $this->assertInstanceOf(Hour::class, $hour);
            $this->assertSame($start->format('Y-m-d H'), $hour->getBegin()->format('Y-m-d H'));
            $this->assertSame('00:00', $hour->getBegin()->format('i:s'));

            $start = $start->add(new \DateInterval('PT1H'));
            $i++;
        }

        $this->assertEquals(24, $i);
    }
}
