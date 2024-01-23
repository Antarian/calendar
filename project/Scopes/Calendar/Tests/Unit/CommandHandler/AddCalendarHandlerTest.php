<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\CommandHandler;

use Antarian\Core\Tests\BaseTestCase;
use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\CommandHandler\AddCalendarHandler;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Symfony\Component\Uid\UuidV6;

class AddCalendarHandlerTest extends BaseTestCase
{
    public AddCalendarHandler $addCalendarHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = $this->createMock(CalendarRepository::class);
        $repository->expects(self::once())->method('store');

        $this->addCalendarHandler = new AddCalendarHandler($repository);
    }

    public function testCalendarHandlerProperlyInvoked()
    {
        $addCalendar = new AddCalendar(title: $this->faker->words(asText: true), id: UuidV6::generate());
        $result = call_user_func($this->addCalendarHandler, $addCalendar);

        $this->assertEquals(new CalendarId($addCalendar->id), $result);
    }
}