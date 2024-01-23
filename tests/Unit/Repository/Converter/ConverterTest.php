<?php
declare(strict_types=1);
namespace Tests\Unit\Repository\Converter;

use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;
use App\Repository\Converter\Converter;
use DateTimeImmutable;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV6;

class ConverterTest extends TestCase
{
    /**
     * @dataProvider objectProvider
     */
    public function testObjectIsConvertedToExpectedArray(object $object, array $expected): void
    {
        $result = Converter::convertToArray($object);

        $this->assertEquals($expected, $result);
    }

    public static function objectProvider(): array
    {
        $faker = Factory::create();
        $endDateTime = $faker->date(DATE_ATOM);
        $startDateTime = $faker->date(DATE_ATOM, $endDateTime);

        return [
            'convert_object_to_array' => [
                'object' => Calendar::create(
                    id: new CalendarId($id = UuidV6::generate()),
                    title: $title = $faker->words(asText: true),
                    events: new CalendarEventCollection([
                        new CalendarEvent(
                            title: $eventTitle = $faker->words(asText: true),
                            startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $startDateTime),
                            endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $endDateTime),
                        ),
                    ])
                ),
                'expected' => [
                    'id' => $id,
                    'title' => $title,
                    'events' => [
                        [
                            'title' => $eventTitle,
                            'startDateTime' => $startDateTime,
                            'endDateTime' => $endDateTime,
                        ],
                    ],
                ],
            ],
        ];
    }
}