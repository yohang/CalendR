<?php

namespace CalendR\Test\Period;

use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Week;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class WeekTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructValid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-02')],
            [new \DateTimeImmutable('2012-01-09')],
            [new \DateTimeImmutable('2012-01-23')],
            [new \DateTime('2012-01-02')],
            [new \DateTime('2012-01-09')],
            [new \DateTime('2012-01-23')],
        ];
    }

    public static function providerContains(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-02'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2012-01-09')],
            [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-19')],
            [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2011-01-10')],
            [new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-09'), new \DateTimeImmutable('2012-01-17')],
            [new \DateTime('2012-01-02'), new \DateTime('2012-01-04'), new \DateTime('2012-01-09')],
            [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-19')],
            [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2011-01-10')],
            [new \DateTime('2012-01-09'), new \DateTime('2012-01-09'), new \DateTime('2012-01-17')],
        ];
    }

    public static function providerNumber(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-02'), 1],
            [new \DateTimeImmutable('2012-01-09'), 2],
            [new \DateTimeImmutable('2011-12-26'), 52],
            [new \DateTime('2012-01-02'), 1],
            [new \DateTime('2012-01-09'), 2],
            [new \DateTime('2011-12-26'), 52],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $week = new Week($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($week->contains($contain));
        $this->assertFalse($week->contains($notContain));
    }

    /**
     * @dataProvider providerNumber
     */
    public function testNumber(\DateTimeInterface $start, int $number): void
    {
        $week = new Week($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertEquals($week->getNumber(), $number);
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $week = new Week($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Week::class, $week);
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-W01');
        $week  = new Week($start, new Factory);

        $i = 0;
        foreach ($week as $dayKey => $day) {
            $this->assertGreaterThan(0, preg_match('/^\\d{2}\\-\\d{2}\\-\\d{4}$/', $dayKey));
            $this->assertSame($start->format('d-m-Y'), $day->getBegin()->format('d-m-Y'));

            $start = $start->add(new \DateInterval('P1D'));

            $i++;
        }

        $this->assertEquals(7, $i);
    }

    public function testGetDatePeriod(): void
    {
        $date = new \DateTimeImmutable('2012-01-01');
        $week = new Week($date, $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($week->getDatePeriod() as $dateTime) {
            $this->assertEquals($date->format('Y-m-d'), $dateTime->format('Y-m-d'));
            $date = $date->add(new \DateInterval('P1D'));
        }
    }

    public function testToString(): void
    {
        $date = new \DateTimeImmutable(date('Y-\\WW'));
        $week = new Week($date, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertSame($date->format('W'), (string)$week);
    }
}
