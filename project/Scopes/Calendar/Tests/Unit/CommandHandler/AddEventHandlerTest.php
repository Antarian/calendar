<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\CommandHandler;

use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\CommandHandler\AddEventHandler;
use Antarian\Scopes\Calendar\Model\Calendar;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use Antarian\Scopes\Calendar\Tests\Unit\Model\CalendarIdTest;
use PHPUnit\Framework\TestCase;

class AddEventHandlerTest extends TestCase
{
    public AddEventHandler $addEventHandler;

    protected function setUp(): void
    {
        $calendar = Calendar::create(
            id: new CalendarId(CalendarIdTest::DUMMY_UUID_V6),
            title: 'My Calendar'
        );

        $repository = $this->createMock(CalendarRepository::class);
        $repository->method('get')->willReturn($calendar);
        $repository->expects(self::once())->method('store');

        $availabilityService = $this->createMock(AvailabilityService::class);
        $availabilityService->method('isSlotAvailable')->willReturn(true);
        $availabilityService->expects(self::once())->method('isSlotAvailable');

        $this->addEventHandler = new AddEventHandler($repository, $availabilityService);
    }

    public function test_calendarHandlerProperlyInvoked()
    {
        $addEvent = new AddEvent(
            calendarId: CalendarIdTest::DUMMY_UUID_V6,
            title: 'My Calendar',
            startDateTime: '2024-01-22T10:30:00+00:00',
            endDateTime: '2024-01-22T11:30:00+00:00',
        );
        $result = call_user_func($this->addEventHandler, $addEvent);

        $this->assertEquals(new CalendarId($addEvent->calendarId), $result);
    }
}