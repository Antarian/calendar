<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Model;

use Antarian\Core\Model\Model;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;

final class Calendar implements Model
{
    public static function create(
        CalendarId $id,
        string $title,
        ?CalendarEventCollection $events = null,
    ): self {
        return new self(
            id: $id,
            title: $title,
            events: $events ?? new CalendarEventCollection()
        );
    }

    public function addEvent(CalendarEvent $calendarEvent): void
    {
        $this->events->offsetSet(null, $calendarEvent);
    }

    public function getId(): CalendarId
    {
        return $this->id;
    }

    private function __construct(
        private CalendarId $id,
        private string $title,
        private CalendarEventCollection $events,
    ) {
    }
}