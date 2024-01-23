<?php
declare(strict_types=1);
namespace Tests\Unit\Repository\Converter;

use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use Antarian\Scopes\Calendar\ValueObject\CalendarEventCollection;
use App\Repository\Converter\Converter;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

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
        return [
            'slot_before_is_available' => [
                'object' => Calendar::create(
                    id: new CalendarId($id = '1eeb911f-9e67-6f44-9be1-6d2d47d8129e'),
                    title: $title = 'My Calendar',
                    events: new CalendarEventCollection([
                        new CalendarEvent(
                            title: $eventTitle = 'My Event',
                            startDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $startDateTime = '2024-01-22T10:30:00+00:00'),
                            endDateTime: DateTimeImmutable::createFromFormat(DATE_ATOM, $endDateTime = '2024-01-22T11:30:00+00:00'),
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