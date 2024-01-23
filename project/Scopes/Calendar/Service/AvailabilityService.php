<?php
namespace Antarian\Scopes\Calendar\Service;

use Antarian\Scopes\Calendar\Model\CalendarId;
use DateTimeImmutable;

interface AvailabilityService
{
    public function isSlotAvailable(CalendarId $calendarId, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): bool;
}