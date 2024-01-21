<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\ValueObject;

use Antarian\Core\ValueObject\ValueObject;
use DateTimeImmutable;

final readonly class CalendarEvent implements ValueObject
{
    public function __construct(
        public string $title,
        public DateTimeImmutable $startDateTime,
        public DateTimeImmutable $endDateTime,
    ) {
    }
}