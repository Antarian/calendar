<?php
use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use App\Repository\CacheCalendarEventRepository;
use App\Repository\CacheCalendarRepository;
use App\Service\AvailabilityVerifier;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Uid\UuidV6;

require_once __DIR__ . '/../vendor/autoload.php';

$cache = new FilesystemAdapter('calendar');
$calendarRepository = new CacheCalendarRepository($cache);
$eventRepository = new CacheCalendarEventRepository($cache);
$availabilityService = new AvailabilityVerifier($eventRepository);

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
$addEventHandler = new AddEventHandler($calendarRepository, $availabilityService);
$addEventHandler($addEvent);

$addEvent = new AddEvent(
    calendarId: $calendarId,
    title: 'My Event',
    startDateTime: "2024-01-22T09:30:00+00:00",
    endDateTime: "2024-01-22T10:30:00+00:00",
);
$addEventHandler = new AddEventHandler($calendarRepository, $availabilityService);
$addEventHandler($addEvent);

$calendar = $calendarRepository->get(new CalendarId($calendarId));

var_dump($calendar);