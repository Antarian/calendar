<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Command;

use Antarian\Scopes\Calendar\Command\AddCalendar;
use Antarian\Scopes\Calendar\Tests\Unit\Model\CalendarIdTest;
use PHPUnit\Framework\TestCase;

class AddCalendarTest extends TestCase
{
    private AddCalendar $addCalendar;
    private string $title;
    private ?string $id;

    protected function setUp(): void
    {
        $this->addCalendar = new AddCalendar(
            $this->title = 'test',
            $this->id = CalendarIdTest::DUMMY_UUID_V6,
        );
    }

    public function testAddCalendarHasCorrectData(): void
    {
        $this->assertSame($this->title, $this->addCalendar->title);
        $this->assertSame($this->id, $this->addCalendar->id);
    }
}