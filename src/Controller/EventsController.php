<?php
declare(strict_types=1);
namespace App\Controller;

use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use App\Exception\ValidationException;
use App\Repository\CacheCalendarRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventsController
{
    public function __construct(
        private CacheCalendarRepository $repository,
        private AvailabilityService $availabilityService,
        private ValidatorInterface $validator,
    ) {
    }

    public function addCalendar(string $title, string $calendarId)
    {
        $command = new AddCalendar($title, $calendarId);
        $violations = $this->validator->validate($command);
        $this->processViolations($violations);

        $commandHandler = new AddCalendarHandler($this->repository);
        $commandHandler($command);
    }

    public function addEvent(string $title, string $calendarId, string $startDateTime, string $endDateTime)
    {
        $command = new AddEvent($calendarId, $title, $startDateTime, $endDateTime);
        $violations = $this->validator->validate($command);
        $this->processViolations($violations);

        $commandHandler = new AddEventHandler($this->repository, $this->availabilityService);
        $commandHandler($command);
    }

    public function getEvents(string $calendarId, string $startDate, string $endDate): array
    {
        return $this->repository->getEventsForDates(
            new CalendarId($calendarId),
            DateTimeImmutable::createFromFormat(DATE_ATOM, $startDate),
            DateTimeImmutable::createFromFormat(DATE_ATOM, $endDate)
        );
    }

    protected function processViolations(ConstraintViolationListInterface $violations): void
    {
        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                $violationMessages[] = sprintf(
                    'Violation \'%s\' for the property \'%s\' with the value \'%s\'',
                    $violation->getMessage(),
                    $violation->getPropertyPath(),
                    $violation->getInvalidValue()
                );
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }
    }
}