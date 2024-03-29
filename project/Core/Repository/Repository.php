<?php
namespace Antarian\Core\Repository;

use Antarian\Core\Exception\NotFoundException;
use Antarian\Core\Model\Model;
use Symfony\Component\Uid\Uuid;

interface Repository
{
    public function nextId(): Uuid;

    /**
     * @throws NotFoundException
     */
    public function get(Uuid $id): Model;

    public function store(Model $model): void;
}