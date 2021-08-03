<?php

namespace CalendR\Test\Period;

use CalendR\Period\Exception\NotASecond;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Second;
use CalendR\Period\Minute;
use CalendR\Period\Hour;
use CalendR\Period\Day;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Year;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SecondTest extends TestCase
{
    use ProphecyTrait;

    public static function providerConstructValid(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-03')],
            [new \DateTimeImmutable('2013-07-13 12:34:56')],
            [new \DateTime('2012-01-03')],
            [new \DateTime('2013-07-13 12:34:56')],
        ];
    }

    public static function providerConstructInvalid(): array
    {
        return [
            // Note that an instance of DateTime with no constructor arguments does not contain microseconds.
            [new \DateTimeImmutable('2014-05-25 17:45:03.167438')],
            [new \DateTime('2014-05-25 17:45:03.167438')],
        ];
    }

    /**
     * @dataProvider providerConstructValid
     */
    public function testConstructValid($start): void
    {
        $second = new Second($start, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertInstanceOf(Second::class, $second);
    }

    /**
     * @dataProvider providerConstructInvalid
     */
    public function testConstructInvalid($start): void
    {
        $this->expectException(NotASecond::class);

        new Second($start, $this->prophesize(FactoryInterface::class)->reveal());
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
                new \DateTimeImmutable('2012-01-02 05:23'),
                new \DateTimeImmutable('2012-01-02 05:23:00'),
                new \DateTimeImmutable('2012-01-02 05:23:01'),
            ],
            [
                new \DateTimeImmutable('2012-05-30 05:23:14'),
                new \DateTimeImmutable('2012-05-30 05:23:14'),
                new \DateTimeImmutable('2012-05-30 05:23:13'),
            ],
            [
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-02'),
                new \DateTime('2012-01-03'),
            ],
            [
                new \DateTime('2012-01-02 05:23'),
                new \DateTime('2012-01-02 05:23:00'),
                new \DateTime('2012-01-02 05:23:01'),
            ],
            [
                new \DateTime('2012-05-30 05:23:14'),
                new \DateTime('2012-05-30 05:23:14'),
                new \DateTime('2012-05-30 05:23:13'),
            ],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($start, $contain, $notContain): void
    {
        $second = new Second($start, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertTrue($second->contains($contain));
        $this->assertFalse($second->contains($notContain));
    }

    public function testGetNext(): void
    {
        $second = new Second(new \DateTime('2012-01-01 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-01 05:00:01', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));

        $second = new Second(new \DateTime('2012-01-31 14:59:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 15:00:00', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));

        $second = new Second(new \DateTime('2013-02-28 23:59:59'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2013-03-01 00:00:00', $second->getNext()->getBegin()->format('Y-m-d H:i:s'));
    }

    public function testGetPrevious(): void
    {
        $second = new Second(new \DateTime('2012-01-01 00:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-31 23:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));

        $second = new Second(new \DateTime('2012-01-31 05:00'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 04:59:59', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));

        $second = new Second(new \DateTime('2012-01-31 05:25:41'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-31 05:25:40', $second->getPrevious()->getBegin()->format('Y-m-d H:i:s'));
    }

    public function testGetDatePeriod(): void
    {
        $second = new Second(new \DateTime('2012-01-31 13:12:27'), $this->prophesize(FactoryInterface::class)->reveal());

        $i = 0;
        foreach ($second->getDatePeriod() as $dateTime) {
            $i++;
            $this->assertEquals('2012-01-31 13:12:27', $dateTime->format('Y-m-d H:i:s'));
        }

        $this->assertSame(1, $i);
    }

    public function testCurrentSecond(): void
    {
        $currentDateTime = new \DateTimeImmutable();
        $otherDateTime   = (clone $currentDateTime)->add(new \DateInterval('PT5S'));

        $currentSecond = new Second(new \DateTimeImmutable($currentDateTime->format('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherSecond   = $currentSecond->getNext();

        $this->assertTrue($currentSecond->contains($currentDateTime));
        $this->assertFalse($currentSecond->contains($otherDateTime));
        $this->assertFalse($otherSecond->contains($currentDateTime));
    }

    public function testToString(): void
    {
        $second = new Second(new \DateTimeImmutable(date('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($second->getBegin()->format('s'), (string)$second);
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Second::isValid(new \DateTimeImmutable('2014-03-05')));
        $this->assertTrue(Second::isValid(new \DateTimeImmutable('2014-03-05 18:00')));
        $this->assertTrue(Second::isValid(new \DateTimeImmutable('2014-03-05 18:36')));
        $this->assertTrue(Second::isValid(new \DateTimeImmutable('2014-03-05 18:36:15')));

        $this->assertTrue(Second::isValid(new \DateTime('2014-03-05')));
        $this->assertTrue(Second::isValid(new \DateTime('2014-03-05 18:00')));
        $this->assertTrue(Second::isValid(new \DateTime('2014-03-05 18:36')));
        $this->assertTrue(Second::isValid(new \DateTime('2014-03-05 18:36:15')));
    }

    /**
     * @dataProvider providerIncludes
     */
    public function testIncludes(\DateTimeInterface $begin, PeriodInterface $period, $strict, $result): void
    {
        $second = new Second($begin, $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame($result, $second->includes($period, $strict));
    }

    public function testFormat(): void
    {
        $second = new Second(new \DateTimeImmutable(date('Y-m-d 15:00:27')), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertSame(date('Y-m-d 15:00:27'), $second->format('Y-m-d H:i:s'));
    }

    public function testIsCurrent(): void
    {
        $currentSecond = new Second(new \DateTime(date('Y-m-d H:i:s')), $this->prophesize(FactoryInterface::class)->reveal());
        $otherSecond   = new Second(new \DateTime('1988-11-12 16:00:50'), $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($currentSecond->isCurrent());
        $this->assertFalse($otherSecond->isCurrent());
    }

    public function providerIncludes(): array
    {
        $factory = $this->prophesize(FactoryInterface::class)->reveal();

        return [
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Year(new \DateTimeImmutable('2013-01-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Day(new \DateTimeImmutable('2013-09-01'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Hour(new \DateTimeImmutable('2013-09-01 12:00'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:34'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:34'), $factory), false, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:00'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Minute(new \DateTimeImmutable('2013-09-01 12:00'), $factory), false, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:34:45'), $factory), true, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:34:45'), $factory), false, false],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:00'), $factory), true, true],
            [new \DateTimeImmutable('2013-09-01 12:00'), new Second(new \DateTimeImmutable('2013-09-01 12:00:00'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Year(new \DateTime('2013-01-01'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Day(new \DateTime('2013-09-01'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Hour(new \DateTime('2013-09-01 12:00'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:34'), $factory), false, false],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:00'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Minute(new \DateTime('2013-09-01 12:00'), $factory), false, true],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), true, false],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:34:45'), $factory), false, false],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), true, true],
            [new \DateTime('2013-09-01 12:00'), new Second(new \DateTime('2013-09-01 12:00:00'), $factory), false, true],
        ];
    }
}
