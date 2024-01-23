<?php
declare(strict_types=1);
namespace App\Repository;

use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CacheCalendarEventRepository
{
    public function __construct(
        private FilesystemAdapter $cache
    ) {
    }

    /**
     * @return array<int, CalendarEvent>
     */
    public function getEventsForDates(CalendarId $calendarId, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): array
    {
        // much easier to get filtered data with real DB
        $cachedEvents = $this->cache->get('events-' . $calendarId->toRfc4122(), function (ItemInterface $item): ?string {
            return null;
        }, 0);

        $cachedEvents = json_decode($cachedEvents);

        $calendarEvents = [];
        foreach ($cachedEvents as $calendarEvent) {
            $calendarEvents[] = new CalendarEvent(
                title: $calendarEvent->title,
                startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->startDateTime),
                endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->endDateTime),
            );
        }

        return array_filter($calendarEvents, function (CalendarEvent $event) use ($startDateTime, $endDateTime) {
            if (($event->startDateTime >= $startDateTime && $event->startDateTime <= $endDateTime) ||
                ($event->endDateTime >= $startDateTime && $event->endDateTime <= $endDateTime)
            ) {
                return true;
            }

            return false;
        });
    }
}