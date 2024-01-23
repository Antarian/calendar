<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Command;

use Antarian\Core\Tests\BaseTestCase;
use Antarian\Scopes\Calendar\Command\AddEvent;
use Symfony\Component\Uid\UuidV6;

class AddEventTest extends BaseTestCase
{
    private AddEvent $addEvent;
    private string $id;
    private string $title;
    private string $startDateTime;
    private string $endDateTime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addEvent = new AddEvent(
            $this->id = UuidV6::generate(),
            $this->title = $this->faker->words(asText: true),
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