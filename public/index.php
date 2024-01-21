<?php
use Antarian\Calendar\Repository\CalendarEventRepository;
use Antarian\Calendar\Service\AvailabilityVerifier;

require_once __DIR__ . '/../vendor/autoload.php';

$eventRepository = new CalendarEventRepository();
$availabilityVerifier = new AvailabilityVerifier($eventRepository);

var_dump($availabilityVerifier->isSlotAvailable(
    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:00:00'),
    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:30:00'),
));

var_dump($availabilityVerifier->isSlotAvailable(
    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 09:30:00'),
    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-02-01 10:00:00'),
));

// output is true and false