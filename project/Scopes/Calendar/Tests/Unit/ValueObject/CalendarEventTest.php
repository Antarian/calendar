<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\ValueObject;

use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CalendarEventTest extends TestCase
{
    public function testCalendarEventCreatedSuccessfully(): void
    {
        $calendarEvent = new CalendarEvent(
            $title = 'test',
            $startDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:00:00'),
            $endDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:30:00')
        );

        $this->assertSame($title, $calendarEvent->title);
        $this->assertSame($startDateTime, $calendarEvent->startDateTime);
        $this->assertSame($endDateTime, $calendarEvent->endDateTime);
    }
}