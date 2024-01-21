<?php
declare(strict_types=1);
namespace App\Repository;

use Antarian\Core\Model\Model;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Symfony\Component\Uid\Uuid;

class InMemoryCalendarRepository implements CalendarRepository
{
    private array $events;

    public function nextId(): CalendarId
    {
        return new CalendarId(Uuid::v6()->toRfc4122());
    }

    public function get(CalendarId|Uuid $id): Calendar
    {
        return $this->events[$id->toRfc4122()];
    }

    public function store(Calendar|Model $model): void
    {
        $this->events[$model->getId()->toRfc4122()] = $model;
    }
}