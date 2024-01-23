<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\CommandHandler;

use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Tests\Unit\Model\CalendarIdTest;
use PHPUnit\Framework\TestCase;

class AddCalendarHandlerTest extends TestCase
{
    public AddCalendarHandler $addCalendarHandler;

    protected function setUp(): void
    {
        $repository = $this->createMock(CalendarRepository::class);
        $repository->expects(self::once())->method('store');

        $this->addCalendarHandler = new AddCalendarHandler($repository);
    }

    public function test_calendarHandlerProperlyInvoked()
    {
        $addCalendar = new AddCalendar(title: 'My Calendar',id: CalendarIdTest::DUMMY_UUID_V6);
        $result = call_user_func($this->addCalendarHandler, $addCalendar);

        $this->assertEquals(new CalendarId($addCalendar->id), $result);
    }
}