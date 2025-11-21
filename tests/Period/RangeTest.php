<?php

namespace CalendR\Test\Period;

use CalendR\Period\Exception\NotImplemented;
use CalendR\Period\FactoryInterface;
use CalendR\Period\Range;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RangeTest extends TestCase
{
    use ProphecyTrait;

    public static function providerContains(): array
    {
        return [
            [new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-06'), new \DateTimeImmutable('2012-01-04'), new \DateTimeImmutable('2013-02-09')],
            [new \DateTimeImmutable('2011-01-03'), new \DateTimeImmutable('2011-01-11'), new \DateTimeImmutable('2011-01-05'), new \DateTimeImmutable('2012-03-19')],
            [new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2013-01-01'), new \DateTimeImmutable('2012-09-09'), new \DateTimeImmutable('2011-10-09')],
            [new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-09'), new \DateTimeImmutable('2013-02-02'), new \DateTimeImmutable('2013-02-09')],
            [new \DateTime('2012-01-01'), new \DateTime('2012-01-06'), new \DateTime('2012-01-04'), new \DateTime('2013-02-09')],
            [new \DateTime('2011-01-03'), new \DateTime('2011-01-11'), new \DateTime('2011-01-05'), new \DateTime('2012-03-19')],
            [new \DateTime('2012-01-01'), new \DateTime('2013-01-01'), new \DateTime('2012-09-09'), new \DateTime('2011-10-09')],
            [new \DateTime('2013-02-02'), new \DateTime('2013-02-09'), new \DateTime('2013-02-02'), new \DateTime('2013-02-09')],
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains(\DateTimeInterface $begin, \DateTimeInterface $end, \DateTimeInterface $contain, \DateTimeInterface $notContain): void
    {
        $range = new Range($begin, $end, $this->prophesize(FactoryInterface::class)->reveal());

        $this->assertTrue($range->contains($contain));
        $this->assertFalse($range->contains($notContain));
    }

    public function testGetNext(): void
    {
        $range = new Range(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-03'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2012-01-03', $range->getNext()->getBegin()->format('Y-m-d'));
    }

    public function testGetPrevious(): void
    {
        $range = new Range(new \DateTimeImmutable('2012-01-01'), new \DateTimeImmutable('2012-01-03'), $this->prophesize(FactoryInterface::class)->reveal());
        $this->assertEquals('2011-12-30', $range->getPrevious()->getBegin()->format('Y-m-d'));
    }

    public function testGetDatePeriod(): void
    {
        $begin = new \DateTimeImmutable('2012-01-01');
        $range = new Range($begin, new \DateTimeImmutable('2012-01-03'), $this->prophesize(FactoryInterface::class)->reveal());

        foreach ($range->getDatePeriod() as $DateTimeImmutable) {
            $this->assertEquals($begin->format('Y-m-d'), $DateTimeImmutable->format('Y-m-d'));
            $begin->add(new \DateInterval('P2D'));
        }
    }

    public function testGetDateInterval(): void
    {
        $this->expectException(NotImplemented::class);

        Range::getDateInterval();
    }
}
