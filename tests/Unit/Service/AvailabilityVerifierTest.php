<?php
declare(strict_types=1);
namespace Tests\Unit\Service;

use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\ValueObject\CalendarEvent;
use App\Repository\CacheCalendarRepository;
use App\Service\AvailabilityVerifier;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV6;

class AvailabilityVerifierTest extends TestCase
{
    /**
     * @dataProvider timeSlotAvailabilityProvider
     */
    public function testTimeSlotAvailability(array $events, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime, bool $expected): void
    {
        $eventRepository = $this->createMock(CacheCalendarRepository::class);
        $eventRepository->method('getEventsForDates')->willReturn($events);

        $availabilityVerifier = new AvailabilityVerifier($eventRepository);
        $result = $availabilityVerifier->isSlotAvailable(new CalendarId(UuidV6::generate()), $startDateTime, $endDateTime);

        $this->assertSame($expected, $result);
    }

    public static function timeSlotAvailabilityProvider(): array
    {
        $events = [
            new CalendarEvent(
                title: 'A',
                startDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                endDateTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
            ),
        ];

        return [
            'slot_before_is_available' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 09:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                'expected' => true,
            ],
            'slot_after_is_available' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 12:00:00'),
                'expected' => true,
            ],
            'slot_is_not_available_end_same_start_before' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:00:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'expected' => false,
            ],
            'slot_is_not_available_end_same_start_same' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'expected' => false,
            ],
            'slot_is_not_available_end_same_start_after' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:45:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'expected' => false,
            ],
            'slot_is_not_available_start_same_end_before' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:00:00'),
                'expected' => false,
            ],
            'slot_is_not_available_start_same_end_same' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'expected' => false,
            ],
            'slot_is_not_available_start_same_end_after' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:30:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 12:00:00'),
                'expected' => false,
            ],
            'slot_is_not_available_start_before_end_after' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 10:00:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 12:00:00'),
                'expected' => false,
            ],
            'slot_is_not_available_start_after_end_before' => [
                'events' => $events,
                'startDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:00:00'),
                'endDateTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-01-22 11:30:00'),
                'expected' => false,
            ],
        ];
    }
}