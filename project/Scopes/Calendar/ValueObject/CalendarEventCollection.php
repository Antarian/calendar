<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\ValueObject;

use Antarian\Core\ValueObject\ValueObjectCollection;

final class CalendarEventCollection extends ValueObjectCollection
{
    protected function getCollectionItemClass(): string
    {
        return CalendarEvent::class;
    }
}