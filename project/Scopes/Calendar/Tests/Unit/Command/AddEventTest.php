<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Command;

use Antarian\Scopes\Calendar\Command\AddEvent;
use Antarian\Scopes\Calendar\Tests\Unit\Model\CalendarIdTest;
use PHPUnit\Framework\TestCase;

class AddEventTest extends TestCase
{
    private AddEvent $addEvent;
    private string $id;
    private string $title;
    private string $startDateTime;
    private string $endDateTime;

    protected function setUp(): void
    {
        $this->addEvent = new AddEvent(
            $this->id = CalendarIdTest::DUMMY_UUID_V6,
            $this->title = 'test',
            $this->startDateTime = '2024-01-22T10:30:00+00:00',
            $this->endDateTime = '2024-01-22T11:30:00+00:00',
        );
    }

    public function testAddCalendarHasCorrectData(): void
    {
        $this->assertSame($this->id, $this->addEvent->calendarId);
        $this->assertSame($this->title, $this->addEvent->title);
        $this->assertSame($this->startDateTime, $this->addEvent->startDateTime);
        $this->assertSame($this->endDateTime, $this->addEvent->endDateTime);
    }
}