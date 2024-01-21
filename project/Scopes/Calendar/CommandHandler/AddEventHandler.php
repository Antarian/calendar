<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\CommandHandler;

use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use DateTimeImmutable;

final readonly class AddEventHandler
{
    public function __construct(
        private CalendarRepository $repository,
    ) {
    }

    public function __invoke(AddEvent $command): CalendarId
    {
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