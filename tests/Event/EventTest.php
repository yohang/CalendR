<?php

namespace CalendR\Test\Event;

use CalendR\Event\Event;
use CalendR\Event\Exception\InvalidEvent;
use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    public function testItValidatesInput(): void
    {
        $this->expectException(InvalidEvent::class);
        $this->expectExceptionMessage('Events usually start before they end');

        new Event(new \DateTimeImmutable('now'), new \DateTimeImmutable('-1 day'));
    }

    public function testItCreatesEvent(): void
    {
        $start = new \DateTimeImmutable('2024-01-01 10:00:00');
        $end = new \DateTimeImmutable('2024-01-01 12:00:00');

        $event = new Event($start, $end);

        $this->assertInstanceOf(Event::class, $event);
    }
}
