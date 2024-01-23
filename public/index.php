<?php
use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use App\Controller\EventsController;
use App\DI\AppContainer;
use Symfony\Component\Uid\UuidV6;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new AppContainer();

$controller = new EventsController(
    $container->get(CalendarRepository::class),
    $container->get(AvailabilityService::class)
);

$controller->addCalendar('My Calendar', $calendarId = (new UuidV6())->toRfc4122());
// first event
$controller->addEvent(
    'My Event',
    $calendarId,
    "2024-01-22T09:30:00+00:00",
    "2024-01-22T10:30:00+00:00"
);
// conflicting second event
$controller->addEvent(
    'My Second Event',
    $calendarId,
    "2024-01-22T09:00:00+00:00",
    "2024-01-22T10:00:00+00:00",
);
// third event
$controller->addEvent(
    'My Third Event',
    $calendarId,
    "2024-01-23T09:30:00+00:00",
    "2024-01-23T10:30:00+00:00"
);
$events22nd = $controller->getEvents($calendarId, "2024-01-22T09:00:00+00:00", "2024-01-23T09:00:00+00:00");
var_dump($events22nd);

$events23rd = $controller->getEvents($calendarId, "2024-01-23T09:00:00+00:00", "2024-01-24T09:00:00+00:00");
var_dump($events23rd);
