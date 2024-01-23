<?php
declare(strict_types=1);
namespace App\Service;

use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use App\Repository\CacheCalendarRepository;
use DateTimeImmutable;

class AvailabilityVerifier implements AvailabilityService
{
    public function __construct(private CacheCalendarRepository $eventRepository)
    {
    }

    public function isSlotAvailable(CalendarId $calendarId, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): bool
    {
        $events = $this->eventRepository->getEventsForDates($calendarId, $startDateTime, $endDateTime);

        foreach ($events as $event) {
            if ($startDateTime < $event->endDateTime &&
                $endDateTime > $event->startDateTime
            ) {
                return false;
            }
        }

        return true;
    }
}