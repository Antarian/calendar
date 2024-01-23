<?php
declare(strict_types=1);

namespace App\Repository\Converter;

use Antarian\Core\Collection\Collection;
use Antarian\Core\Model\Model;
use Antarian\Core\ValueObject\ValueObject;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

class Converter
{
    public static function convertToArray(object $object): array
    {
        $reflection = new \ReflectionClass($object);

        $objectArray = [];
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($object);

            if ($propertyValue instanceof DateTimeInterface) {
                $propertyValue = $propertyValue->format(DATE_ATOM);
            }

            if ($propertyValue instanceof Uuid) {
                $propertyValue = $propertyValue->toRfc4122();
            }

            if ($propertyValue instanceof Model) {
                $propertyValue = self::convertToArray($propertyValue);
                $propertyValue = $propertyValue['id'];
                $propertyName .= 'Id';
            }

            if ($propertyValue instanceof Collection) {
                $propertyValues = [];
                foreach ($propertyValue as $key => $collectionValue) {
                    $propertyValues[$key] = self::convertToArray($collectionValue);
                }
                $propertyValue = $propertyValues;
            }

            if ($propertyValue instanceof ValueObject) {
                $propertyValue = self::convertToArray($propertyValue);
            }

            $objectArray[$propertyName] = $propertyValue;
        }

        return $objectArray;
    }
}