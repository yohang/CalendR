<?php

declare(strict_types=1);

namespace CalendR\Test\Period;

use CalendR\Period\Day;
use CalendR\Period\Exception\NotAYear;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Month;
use CalendR\Period\Year;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class YearTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-03')];
        yield [new \DateTimeImmutable('2014-12-10')];
        yield [new \DateTimeImmutable('2014-01-01 00:00:01')];
        yield [new \DateTime('2012-01-03')];
        yield [new \DateTime('2014-12-10')];
        yield [new \DateTime('2014-01-01 00:00:01')];
    }

    public static function providerConstructValid(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-01')];
        yield [new \DateTimeImmutable('2011-01-01')];
        yield [new \DateTimeImmutable('2013-01-01')];
        yield [new \DateTimeImmutable('2014-01-01 00:00')];
        yield [new \DateTime('2012-01-01')];
        yield [new \DateTime('2011-01-01')];
        yield [new \DateTime('2013-01-01')];
        yield [new \DateTime('2014-01-01 00:00')];
    }

    public static function providerContains(): \Iterator
    {
        yield [new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2013-02-09')];
        yield [new \DateTimeImmutable('2011-01-01'), new \DateTimeImmutable('2011-01-01'), new \DateTimeImmutable('2012-03-19')];
        yield [new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2013-09-09'), new \DateTimeImmutable('2011-10-09')];
        yield [new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2013-12-31'), new \DateTimeImmutable('2014-01-01')];
        yield [new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2013-12-31'), new \DateTimeImmutable('2014-01-01')];
        yield [new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2014-01-01')];
        yield [new \DateTime('2012-01-01'), new \DateTime('2012-01-04'), new \DateTime('2013-02-09')];
        yield [new \DateTime('2011-01-01'), new \DateTime('2011-01-01'), new \DateTime('2012-03-19')];
        yield [new \DateTime('2013-01-01'), new \DateTime('2013-09-09'), new \DateTime('2011-10-09')];
        yield [new \DateTime('2013-01-01'), new \DateTime('2013-12-31'), new \DateTime('2014-01-01')];
        yield [new \DateTime('2013-01-01'), new \DateTime('2013-12-31'), new \DateTime('2014-01-01')];
        yield [new \DateTime('2013-01-01'), new \DateTime('2013-01-01'), new \DateTime('2014-01-01')];
    }

    #[DataProvider('providerContains')]
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $year = new Year($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($year->contains($contain));
        $this->assertFalse($year->contains($notContain));
    }

    #[DataProvider('providerContains')]
    public function testIncludes(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $year = new Year($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($year->includes(new Day($contain, $this->prophesize(FactoryInterface::class)->reveal())));
        $this->assertFalse($year->includes(new Day($notContain, $this->prophesize(FactoryInterface::class)->reveal())));
    }

    #[DataProvider('providerConstructInvalid')]
    public function testConstructInvalid(\DateTimeInterface $start): void
    {
        $this->expectException(NotAYear::class);

        new Year($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    #[DataProvider('providerConstructValid')]
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $year = new Year($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Year::class, $year);
    }

    public function testToString(): void
    {
        $year = new Year(new \DateTime('2014-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2014', (string) $year);
    }

    public function testIteration(): void
    {
        $start = new \DateTime('2012-01');
        $year = new Year($start, new Factory());

        $i = 0;

        foreach ($year as $monthKey => $month) {
            $this->assertIsInt($monthKey);
            $this->assertSame((int) $month->getBegin()->format('m'), $monthKey);
            $this->assertInstanceOf(Month::class, $month);
            $this->assertSame($start->format('d-m-Y'), $month->getBegin()->format('d-m-Y'));
            $start = $start->add(new \DateInterval('P1M'));
            ++$i;
        }

        $this->assertSame(12, $i);
    }

    public function testGetNext(): void
    {
        $year = new Year(new \DateTime('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2013-01-01', $year->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $year = new Year(new \DateTime('2012-01-01'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame('2011-01-01', $year->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $date = new \DateTime('2012-01-01');
        $year = new Year($date, $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($year->getDatePeriod() as $dateTime) {
            $this->assertSame($date->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $date = $date->add(new \DateInterval('P1D'));
        }
    }
}
