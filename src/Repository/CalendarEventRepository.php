<?php
declare(strict_types=1);
namespace App\Repository;

use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;

class CalendarEventRepository
{
    private array $events;

    public function __construct()
    {
        $this->populateEvents();
    }

    /**
     * @return array<int, CalendarEvent>
     */
    public function getEventsForDates(DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): array
    {
        // SQL server usually does this
        return array_filter($this->events, function (CalendarEvent $event) use ($startDateTime, $endDateTime) {
            if (($event->startDateTime >= $startDateTime && $event->startDateTime <= $endDateTime) ||
                ($event->endDateTime >= $startDateTime && $event->endDateTime <= $endDateTime)
            ) {
                return true;
            }

            return false;
        });
    }

    private function populateEvents(): void
    {
        $this->events = [
            new CalendarEvent(
                title: 'Event A',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 10:00:00'),
            ),
            new CalendarEvent(
                title: 'Event B',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-01-31 14:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-01-31 16:00:00'),
            ),
            new CalendarEvent(
                title: 'Event C',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-01-31 22:00:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:00:00'),
            ),
            new CalendarEvent(
                title: 'Event D',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 11:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 12:30:00'),
            ),
            new CalendarEvent(
                title: 'Event E',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 12:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 13:00:00'),
            ),
            new CalendarEvent(
                title: 'Event F',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 14:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 16:00:00'),
            ),
        ];
    }

}