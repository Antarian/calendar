<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Helper;

class ReflectionHelper
{
    public static function getProperty(object $object, string $property): mixed
    {
        $reflectedClass = new \ReflectionClass($object);
        $reflection = $reflectedClass->getProperty($property);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }
}