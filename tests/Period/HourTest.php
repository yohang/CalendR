<?php

namespace CalendR\Test\Period;

use CalendR\Period\Exception\NotAnHour;
use CalendR\Period\Factory;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class HourTest extends TestCase
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
        $this->expectException(NotAnHour::class);

        new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid(\DateTimeInterface $start): void
    {
        $hour = new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Hour::class, $hour);
    }

    public static function providerContains(): array
    {
        return [
            [
                new \DateTimeImmutable('2012-01-02'),
                new \DateTimeImmutable('2012-01-02 00:01'),
                new \DateTimeImmutable('2012-01-02 12:34'),
            ],
            [
                new \DateTimeImmutable('2012-05-30 05:00'),
                new \DateTimeImmutable('2012-05-30 05:00'),
                new \DateTimeImmutable('2012-05-30 06:00'),
            ],
            [
                new \DateTimeImmutable('2012-09-09 05:00'),
                new \DateTimeImmutable('2012-09-09 05:00:01'),
                new \DateTimeImmutable('2011-08-09 05:30'),
            ],
            [
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02 00:01'),
                new \DateTime('2012-01-02 12:34'),
            ],
            [
                new \DateTime('2012-05-30 05:00'),
                new \DateTime('2012-05-30 05:00'),
                new \DateTime('2012-05-30 06:00'),
            ],
            [
                new \DateTime('2012-09-09 05:00'),
                new \DateTime('2012-09-09 05:00:01'),
                new \DateTime('2011-08-09 05:30'),
            ],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains(\DateTimeInterface $start, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $hour = new Hour($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($hour->contains($contain));
        $this->assertFalse($hour->contains($notContain));
    }

    public function testGetNext(): void
    {
        $hour = new Hour(new \DateTimeImmutable('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 06:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));

        $hour = new Hour(new \DateTimeImmutable('2012-01-31 14:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));

        $hour = new Hour(new \DateTimeImmutable('2013-02-28 23:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00', $hour->getNext()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetPrevious(): void
    {
        $hour = new Hour(new \DateTimeImmutable('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));

        $hour = new Hour(new \DateTimeImmutable('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));

        $hour = new Hour(new \DateTimeImmutable('2012-01-31 06:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:00', $hour->getPrevious()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetDatePeriod(): void
    {
        $hour = new Hour(new \DateTimeImmutable('2012-01-31 13:00'), $this->prophesize(FactoryInterface::class)->reveal());

        $i = 0;
        foreach ($hour->getDatePeriod() as $DateTimeImmutable) {
            $i++;
            $this->assertEquals('2012-01-31 13', $DateTimeImmutable->format('Y-m-d H'));
        }

        $this->assertSame(60, $i);
    }

    public function testCurrentHour(): void
    {
        $currentDateTimeImmutable = new \DateTimeImmutable();
        $otherDateTimeImmutable   = (clone $currentDateTimeImmutable)->add(new \DateInterval('PT5H'));

        $currentHour = new Hour(new \DateTimeImmutable(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherHour   = $currentHour->getNext();

        $this->assertTrue($currentHour->contains($currentDateTimeImmutable));
        $this->assertFalse($currentHour->contains($otherDateTimeImmutable));
        $this->assertFalse($otherHour->contains($currentDateTimeImmutable));
    }

    public function testToString(): void
    {
        $hour = new Hour(new \DateTimeImmutable(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($hour->getBegin()->format('G'), (string)$hour);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Hour::isValid(new \DateTimeImmutable('2014-03-05')));
        $this->assertTrue(Hour::isValid(new \DateTimeImmutable('2014-03-05 18:00')));
        $this->assertFalse(Hour::isValid(new \DateTimeImmutable('2014-03-05 18:36')));
        $this->assertFalse(Hour::isValid(new \DateTimeImmutable('2014-03-05 18:00:01')));
    }

    /**
     * @dataProvider includesDataProvider
     */
    public function testIncludes(\DateTimeInterface  $begin, PeriodInterface $period, bool $strict, bool $result): void
    {
        $hour = new Hour($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $hour->includes($period, $strict));
    }

    public function testFormat(): void
    {
        $hour = new Hour(new \DateTimeImmutable(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d H:00'), $hour->format('Y-m-d H:i'));
    }

    public function testIsCurrent(): void
    {
        $currentHour = new Hour(new \DateTimeImmutable(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherHour   = new Hour(new \DateTimeImmutable('1988-11-12 16:00'), $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($currentHour->isCurrent());
        $this->assertFalse($otherHour->isCurrent());
    }

    public function includesDataProvider(): array
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return [
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:34'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:34'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:34:45'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:34:45'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), true, true],
            [new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), true, true],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, true],
        ];
    }

    public function testIteration(): void
    {
        $start = new \DateTimeImmutable('2012-01-15 13:00');
        $hour  = new Hour($start, new Factory());

        $i = 0;
        foreach ($hour as $minuteKey => $minute) {
            $this->assertTrue(is_int($minuteKey) && $minuteKey >= 0 && $minuteKey < 60);
            $this->assertInstanceOf(Minute::class, $minute);
            $this->assertSame($start->format('Y-m-d H:i'), $minute->getBegin()->format('Y-m-d H:i'));
            $this->assertSame('00', $minute->getBegin()->format('s'));

            $start = $start->add(new \DateInterval('PT1M'));
            $i++;
        }

        $this->assertEquals($i, 60);
    }
}
