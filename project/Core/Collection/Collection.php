<?php
declare(strict_types=1);

namespace Antarian\Core\Collection;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;

abstract class Collection implements ArrayAccess, Countable, Iterator
{
    private array $items = [];

    /**
     * This constructor is there in order to be able to create a collection with
     * its values already added
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $item) {
            $this->offsetSet($key, $item);
        }
    }

    abstract protected function getCollectionItemClass(): string;

    /**
     * Implementation of method declared in \Countable.
     * Provides support for count()
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Implementation of method declared in \Iterator
     * Resets the internal cursor to the beginning of the array
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * Implementation of method declared in \Iterator
     * Used to get the current key (as for instance in a foreach()-structure
     */
    public function key(): int|string|null
    {
        return key($this->items);
    }

    /**
     * Implementation of method declared in \Iterator
     * Used to get the value at the current cursor position
     */
    public function current(): mixed
    {
        return current($this->items);
    }

    /**
     * Implementation of method declared in \Iterator
     * Used to move the cursor to the next position
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * Implementation of method declared in \Iterator
     * Checks if the current cursor position is valid
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * Implementation of method declared in \ArrayAccess
     * Used to be able to use functions like isset()
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Implementation of method declared in \ArrayAccess
     * Used for direct access array-like ($collection[$offset]);
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof ($this->getCollectionItemClass())) {
            throw new InvalidArgumentException(sprintf(
                '%s is only accepting instances of %s. %s given.',
                static::class,
                $this->getCollectionItemClass(),
                get_class($value)
            ));
        }

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Implementation of method declared in \ArrayAccess
     * Used for unset()
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}