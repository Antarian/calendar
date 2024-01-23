<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\ValueObject;

use Antarian\Core\Collection\Collection;

final class CalendarEventCollection extends Collection
{
    protected function getCollectionItemClass(): string
    {
        return CalendarEvent::class;
    }
}