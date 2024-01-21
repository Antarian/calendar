<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\CommandHandler;

use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;

final readonly class AddCalendarHandler
{
    public function __construct(
        private CalendarRepository $repository,
    ) {
    }

    public function __invoke(AddCalendar $command): CalendarId
    {
        $calendar = Calendar::create(
            id: $command->id ? new CalendarId($command->id) : $this->repository->nextId(),
            title: $command->title,
        );

        $this->repository->store($calendar);

        return $calendar->getId();
    }
}