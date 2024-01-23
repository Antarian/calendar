<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\ValueObject;

use Antarian\Core\Collection\Collection;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalendarEventCollectionTest extends TestCase
{
    private CalendarEvent $calendarEvent;
    private CalendarEventCollection $calendarEventCollection;

    protected function setUp(): void
    {
        $this->calendarEvent = new CalendarEvent(
            'test',
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:00:00'),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:30:00')
        );

        $this->calendarEventCollection = new CalendarEventCollection([]);
    }

    public function testCalendarEventCollectionIsVOCollection()
    {
        $this->assertInstanceOf(Collection::class, $this->calendarEventCollection);
    }

    public function testCalendarEventCollectionAcceptingCalendarEvents(): void
    {
        $this->calendarEventCollection->offsetSet(0, $this->calendarEvent);
        $result = $this->calendarEventCollection->offsetGet(0);

        $this->assertSame($this->calendarEvent, $result);
    }

    public function testCalendarEventCollectionAcceptingOnlyCalendarEvents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection is only accepting instances of Antarian\Scopes\Calendar\ValueObject\CalendarEvent. stdClass given.');
        $this->calendarEventCollection->offsetSet(0, new \stdClass());
    }
}