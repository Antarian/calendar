<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Command;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddEvent
{
    public function __construct(
        #[Assert\Uuid(versions: [
            Assert\Uuid::V4_RANDOM,
            Assert\Uuid::V6_SORTABLE
        ])]
        public string $calendarId,

        #[Assert\NotBlank()]
        public string $title,

        #[Assert\DateTime(format: DATE_ATOM)]
        public string $startDateTime,

        #[Assert\DateTime(format: DATE_ATOM)]
        public string $endDateTime,
    ) {
    }
}