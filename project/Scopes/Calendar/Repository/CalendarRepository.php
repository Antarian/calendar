<?php
namespace Antarian\Scopes\Calendar\Repository;

use Antarian\Core\Exception\NotFoundException;
use Antarian\Core\Model\Model;
use Antarian\Core\Repository\Repository;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Symfony\Component\Uid\Uuid;

interface CalendarRepository extends Repository
{
    public function nextId(): CalendarId;

    /**
     * @throws NotFoundException
     */
    public function get(Uuid|CalendarId $id): Calendar;

    public function store(Model|Calendar $model): void;
}