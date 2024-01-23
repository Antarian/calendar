<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Model;

use Antarian\Core\Model\Model;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Tests\Helper\ReflectionHelper;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    public function testCalendarData()
    {
        $calendar = Calendar::create(
            id: $id = CalendarId::fromString(CalendarIdTest::DUMMY_UUID_V6),
            title: $title = 'My Calendar',
            events: $events = new CalendarEventCollection([
                new CalendarEvent(
                    title: 'My Event',
                    startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:00:00'),
                    endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-21 11:30:00'),
                )
            ]),
        );

        $calendarTitle = ReflectionHelper::getProperty($calendar, 'title');
        $calendarEvents = ReflectionHelper::getProperty($calendar, 'events');

        $this->assertSame($id, $calendar->getId());
        $this->assertSame($title, $calendarTitle);
        $this->assertSame($events, $calendarEvents);
    }

    public function testCalendarInterface()
    {
        $this->assertInstanceOf(Model::class, Calendar::create(
            id: CalendarId::fromString(CalendarIdTest::DUMMY_UUID_V6),
            title: 'My Calendar',
        ));
    }


}