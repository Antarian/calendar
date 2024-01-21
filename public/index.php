<?php

use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use App\Repository\CalendarEventRepository;
use App\Repository\InMemoryCalendarRepository;
use App\Service\AvailabilityVerifier;
use Symfony\Component\Uid\UuidV6;

require_once __DIR__ . '/../vendor/autoload.php';

//$eventRepository = new CalendarEventRepository();
//$availabilityVerifier = new AvailabilityVerifier($eventRepository);
//
//var_dump($availabilityVerifier->isSlotAvailable(
//    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:00:00'),
//    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:30:00'),
//));
//
//var_dump($availabilityVerifier->isSlotAvailable(
//    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:30:00'),
//    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 10:00:00'),
//));
//
//// output is true and false

$calendarRepository = new InMemoryCalendarRepository();

$addCalendar = new AddCalendar(
    title: 'My Calendar',
    id: $calendarId = (new UuidV6())->toRfc4122(),
);
$addCalendarHandler = new AddCalendarHandler($calendarRepository);
$addCalendarHandler($addCalendar);

$addEvent = new AddEvent(
    calendarId: $calendarId,
    title: 'My Event',
    startDateTime: "2024-01-22T09:30:00+00:00",
    endDateTime: "2024-01-22T10:30:00+00:00",
);
$addEventHandler = new AddEventHandler($calendarRepository);
$addEventHandler($addEvent);

var_dump($calendarRepository->get(new CalendarId($calendarId)));
