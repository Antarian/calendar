<?php
declare(strict_types=1);
namespace App\Repository;

use Antarian\Core\Exception\NotFoundException;
use Antarian\Core\Model\Model;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;
use App\Repository\Converter\Converter;
use DateTimeImmutable;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\ItemInterface;

class CacheCalendarRepository implements CalendarRepository
{
    private const EVENTS_CACHE_KEY = 'events-';
    private const CALENDAR_CACHE_KEY = 'calendar-';

    public function __construct(
        private FilesystemAdapter $cache
    ) {
    }

    public function nextId(): CalendarId
    {
        return new CalendarId(Uuid::v6()->toRfc4122());
    }

    /**
     * @throws NotFoundException
     */
    public function get(CalendarId|Uuid $id): Calendar
    {
        $cachedCalendar = $this->cache->get(self::CALENDAR_CACHE_KEY . $id->toRfc4122(), function (): ?string {
            return null;
        }, 0);

        if ($cachedCalendar === null) {
            throw new NotFoundException();
        }

        $cachedCalendar = json_decode($cachedCalendar);

        $cachedCalendarEvents = $this->cache->get(self::EVENTS_CACHE_KEY . $id->toRfc4122(), function (): string {
            return '[]';
        }, 0);
        $cachedCalendarEvents = json_decode($cachedCalendarEvents);
        $calendarEvents = [];
        foreach ($cachedCalendarEvents as $calendarEvent) {
            $calendarEvents[] = new CalendarEvent(
                title: $calendarEvent->title,
                startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->startDateTime),
                endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->endDateTime),
            );
        }

        return Calendar::create(
            id: new CalendarId($cachedCalendar->id),
            title: $cachedCalendar->title,
            events: new CalendarEventCollection($calendarEvents),
        );
    }

    public function store(Calendar|Model $model): void
    {
        $idString = $model->getId()->toRfc4122();
        $modelData = Converter::convertToArray($model);

        // simulating 2 tables
        $this->cache->delete(self::EVENTS_CACHE_KEY . $idString);
        $this->cache->delete(self::CALENDAR_CACHE_KEY . $idString);

        $this->cache->get(self::EVENTS_CACHE_KEY . $idString, function (ItemInterface $item) use ($modelData): string {
            $item->expiresAfter(3600);
            return json_encode($modelData['events']);
        }, 0);

        $modelData['events'] = [];
        $this->cache->get(self::CALENDAR_CACHE_KEY . $idString, function (ItemInterface $item) use ($modelData): string {
            $item->expiresAfter(3600);
            return json_encode($modelData);
        }, 0);
    }

    /**
     * @return array<int, CalendarEvent>
     */
    public function getEventsForDates(CalendarId $calendarId, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): array
    {
        $cachedEvents = $this->cache->get(self::EVENTS_CACHE_KEY . $calendarId->toRfc4122(), function (ItemInterface $item): ?string {
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