<?php
declare(strict_types=1);
namespace Antarian\Calendar\Model;

use DateTimeImmutable;

final readonly class CalendarEvent
{
    public function __construct(
        public string $title,
        public DateTimeImmutable $startDateTime,
        public DateTimeImmutable $endDateTime,
    ) {
    }
}