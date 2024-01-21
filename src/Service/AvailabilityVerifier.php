<?php
declare(strict_types=1);
namespace Antarian\Calendar\Service;

use Antarian\Calendar\Repository\CalendarEventRepository;
use DateTimeImmutable;

class AvailabilityVerifier
{
    public function __construct(private CalendarEventRepository $eventRepository)
    {
    }

    public function isSlotAvailable(DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): bool
    {
        $events = $this->eventRepository->getEventsForDates($startDateTime, $endDateTime);

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