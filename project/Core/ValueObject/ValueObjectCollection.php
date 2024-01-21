<?php
declare(strict_types=1);

namespace Antarian\Core\ValueObject;

use InvalidArgumentException;
use Antarian\Core\Collection\Collection;

abstract class ValueObjectCollection extends Collection
{
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof ValueObject) {
            throw new InvalidArgumentException(sprintf(
                '%s is only accepting instances of %s. %s given.',
                self::class,
                ValueObject::class,
                get_class($value)
            ));
        }

        parent::offsetSet($offset, $value);
    }
}