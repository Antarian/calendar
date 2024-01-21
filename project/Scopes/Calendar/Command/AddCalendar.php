<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Command;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddCalendar
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $title,

        #[Assert\Uuid(versions: [
            Assert\Uuid::V4_RANDOM,
            Assert\Uuid::V6_SORTABLE
        ])]
        public ?string $id = null,
    ) {
    }
}