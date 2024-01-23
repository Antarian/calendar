<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\CommandHandler;

use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;

final readonly class AddEventHandler
{
    public function __construct(
        private CalendarRepository $repository,
        private AvailabilityService $availabilityService,
    ) {
    }

    public function __invoke(AddEvent $command): CalendarId
    {
        $calendarId = new CalendarId($command->calendarId);
        $startDateTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $command->startDateTime);
        $endDateTime = DateTimeImmutable::createFromFormat(DATE_ATOM, $command->endDateTime);

        $available = $this->availabilityService->isSlotAvailable($calendarId, $startDateTime, $endDateTime);

        if (!$available) {
            // we may fire event or return false, depending on the requirements of the system and sync/async status of commands
            return $calendarId;
        }

        $calendar = $this->repository->get(new CalendarId($command->calendarId));
        $calendar->addEvent(
            new CalendarEvent(
                title: $command->title,
                startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $command->startDateTime),
                endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $command->endDateTime),
            )
        );
        $this->repository->store($calendar);

        return $calendar->getId();
    }
}