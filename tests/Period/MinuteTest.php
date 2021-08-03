<?php

namespace CalendR\Test\Period;

use CalendR\Period\Exception\NotAMinute;
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

class MinuteTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructInvalid(): array
    {
        return [
            [new \DateTimeImmutable('2014-12-10 17:30:34')],
            [new \DateTimeImmutable('2014-12-10 00:00:01')],
        ];
    }

    public static function providerConstructValid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-03')],
            [new \DateTimeImmutable('2011-12-10 17:45')],
            [new \DateTimeImmutable('2013-07-13 00:00:00')],
        ];
    }

    /**
     * @dataProvider providerConstructInvalid
     */
    public function testConstructInvalid($start): void
    {
        $this->expectException(NotAMinute::class);

        new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start): void
    {
        $minute = new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Minute::class, $minute);
    }

    public static function providerContains(): array
    {
        return [
            [
                new \DateTimeImmutable('2012-01-02'),
                new \DateTimeImmutable('2012-01-02'),
                new \DateTimeImmutable('2012-01-03'),
            ],
            [
                new \DateTimeImmutable('2012-01-02'),
                new \DateTimeImmutable('2012-01-02 00:00:34'),
                new \DateTimeImmutable('2012-01-02 00:01:00'),
            ],
            [
                new \DateTimeImmutable('2012-05-30 05:23'),
                new \DateTimeImmutable('2012-05-30 05:23:23'),
                new \DateTimeImmutable('2012-05-30'),
            ],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain): void
    {
        $minute = new Minute($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($minute->contains($contain));
        $this->assertFalse($minute->contains($notContain));
    }

    public function testGetNext(): void
    {
        $minute = new Minute(new \DateTimeImmutable('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 05:01', $minute->getNext()->getBegin()->format('Y-m-d H:i'));

        $minute = new Minute(new \DateTimeImmutable('2012-01-31 14:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));

        $minute = new Minute(new \DateTimeImmutable('2013-02-28 23:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00', $minute->getNext()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetPrevious(): void
    {
        $minute = new Minute(new \DateTimeImmutable('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));

        $minute = new Minute(new \DateTimeImmutable('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:59', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));

        $minute = new Minute(new \DateTimeImmutable('2012-01-31 05:25'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:24', $minute->getPrevious()->getBegin()->format('Y-m-d H:i'));
    }

    public function testGetDatePeriod(): void
    {
        $minute = new Minute(new \DateTimeImmutable('2012-01-31 13:12'), $this->prophesize(FactoryInterface::class)->reveal());
        $i      = 0;
        foreach ($minute->getDatePeriod() as $DateTimeImmutable) {
            $i++;
            $this->assertEquals('2012-01-31 13:12', $DateTimeImmutable->format('Y-m-d H:i'));
        }

        $this->assertSame(60, $i);
    }

    public function testCurrentMinute(): void
    {
        $currentDateTimeImmutable = new \DateTimeImmutable();
        $otherDateTimeImmutable   = (clone $currentDateTimeImmutable)->add(new \DateInterval('PT5M'));

        $currentMinute = new Minute(new \DateTimeImmutable(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMinute   = $currentMinute->getNext();

        $this->assertTrue($currentMinute->contains($currentDateTimeImmutable));
        $this->assertFalse($currentMinute->contains($otherDateTimeImmutable));
        $this->assertFalse($otherMinute->contains($currentDateTimeImmutable));
    }

    public function testToString(): void
    {
        $minute = new Minute(new \DateTimeImmutable(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($minute->getBegin()->format('i'), (string)$minute);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Minute::isValid(new \DateTimeImmutable('2014-03-05')));
        $this->assertTrue(Minute::isValid(new \DateTimeImmutable('2014-03-05 18:00')));
        $this->assertTrue(Minute::isValid(new \DateTimeImmutable('2014-03-05 18:36')));
        $this->assertFalse(Minute::isValid(new \DateTimeImmutable('2014-03-05 18:36:15')));
    }

    /**
     * @dataProvider providerIncludes
     */
    public function testIncludes(\DateTimeImmutable $begin, PeriodInterface $period, $strict, $result): void
    {
        $minute = new Minute($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $minute->includes($period, $strict));
    }

    public function testFormat(): void
    {
        $minute = new Minute(new \DateTimeImmutable(date('Y-m-d H:00')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d H:00'), $minute->format('Y-m-d H:i'));
    }

    public function testIsCurrent(): void
    {
        $currentMinute = new Minute(new \DateTimeImmutable(date('Y-m-d H:i')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherMinute   = new Minute(new \DateTimeImmutable('1988-11-12 16:00'), $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($currentMinute->isCurrent());
        $this->assertFalse($otherMinute->isCurrent());
    }

    public function providerIncludes(): array
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return [
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:00'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:34'), $factory), false, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:00'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:00'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:30'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:30'), $factory), false, true],
        ];
    }

    public function testIteration(): void
    {
        $start  = new \DateTimeImmutable('2012-01-15 15:47');
        $minute = new Minute($start, new Factory());

        $i = 0;
        foreach ($minute as $secondKey => $second) {
            $this->assertTrue(is_int($secondKey) && $secondKey >= 0 && $secondKey < 60);
            $this->assertInstanceOf(Second::class, $second);
            $this->assertSame($start->format('Y-m-d H:i:s'), $second->getBegin()->format('Y-m-d H:i:s'));

            $start = $start->add(new \DateInterval('PT1S'));
            $i++;
        }

        $this->assertEquals($i, 60);
    }
}
