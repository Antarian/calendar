<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\ValueObject;

use Antarian\Core\ValueObject\ValueObject;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CalendarEventTest extends TestCase
{
    private CalendarEvent $calendarEvent;
    private string $title;
    private DateTimeImmutable $startDateTime;
    private DateTimeImmutable $endDateTime;

    protected function setUp(): void
    {
        $this->calendarEvent = new CalendarEvent(
            $this->title = 'test',
            $this->startDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:00:00'),
            $this->endDateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:30:00')
        );
    }

    public function testCalendarEventInterface()
    {
        $this->assertInstanceOf(ValueObject::class, $this->calendarEvent);
    }

    public function testCalendarEventHasCorrectData(): void
    {
        $this->assertSame($this->title, $this->calendarEvent->title);
        $this->assertSame($this->startDateTime, $this->calendarEvent->startDateTime);
        $this->assertSame($this->endDateTime, $this->calendarEvent->endDateTime);
    }
}