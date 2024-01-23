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
use DateTimeZone;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\ItemInterface;

class CacheCalendarRepository implements CalendarRepository
{
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
        $cachedItem = $this->cache->get('calendar-' . $id->toRfc4122(), function (ItemInterface $item): ?string {
            return null;
        }, 0);

        if ($cachedItem === null) {
            throw new NotFoundException();
        }

        $calendarEvents = [];
        $cachedItem = json_decode($cachedItem);
        foreach ($cachedItem->events as $calendarEvent) {
            $calendarEvents[] = new CalendarEvent(
                title: $calendarEvent->title,
                startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->startDateTime),
                endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $calendarEvent->endDateTime),
            );
        }

        return Calendar::create(
            id: $id,
            title: $cachedItem->title,
            events: new CalendarEventCollection($calendarEvents),
        );
    }

    public function store(Calendar|Model $model): void
    {
        $modelData = Converter::convertToArray($model);

        $this->cache->delete('events-' . $model->getId()->toRfc4122());
        $this->cache->delete('calendar-' . $model->getId()->toRfc4122());

        $events = $this->cache->get('events-' . $model->getId()->toRfc4122(), function (ItemInterface $item) use ($modelData): string {
            $item->expiresAfter(3600);
            return json_encode($modelData['events']);
        }, 0);

        $this->cache->get('calendar-' . $model->getId()->toRfc4122(), function (ItemInterface $item) use ($modelData): string {
            $item->expiresAfter(3600);
            return json_encode($modelData);
        }, 0);
    }
}