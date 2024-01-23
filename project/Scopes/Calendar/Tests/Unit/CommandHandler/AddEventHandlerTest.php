<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\CommandHandler;

use Antarian\Core\Tests\BaseTestCase;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use Symfony\Component\Uid\UuidV6;

class AddEventHandlerTest extends BaseTestCase
{
    public AddEventHandler $addEventHandler;
    public string $calendarId;

    protected function setUp(): void
    {
        parent::setUp();

        $calendar = Calendar::create(
            id: new CalendarId($this->calendarId = UuidV6::generate()),
            title: $this->faker->words(asText: true),
        );

        $repository = $this->createMock(CalendarRepository::class);
        $repository->method('get')->willReturn($calendar);
        $repository->expects(self::once())->method('store');

        $availabilityService = $this->createMock(AvailabilityService::class);
        $availabilityService->method('isSlotAvailable')->willReturn(true);
        $availabilityService->expects(self::once())->method('isSlotAvailable');

        $this->addEventHandler = new AddEventHandler($repository, $availabilityService);
    }

    public function testCalendarEventHandlerProperlyInvoked()
    {
        $addEvent = new AddEvent(
            calendarId: $this->calendarId,
            title: $this->faker->words(asText: true),
            startDateTime: '2024-01-22T10:30:00+00:00',
            endDateTime: '2024-01-22T11:30:00+00:00',
        );
        $result = call_user_func($this->addEventHandler, $addEvent);

        $this->assertEquals(new CalendarId($this->calendarId), $result);
    }
}