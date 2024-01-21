<?php
namespace Antarian\Core\Model;

use Symfony\Component\Uid\Uuid;

interface Model
{
    public function getId(): Uuid;
}