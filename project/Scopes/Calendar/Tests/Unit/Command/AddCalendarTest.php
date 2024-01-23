<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Command;

use Antarian\Core\Tests\BaseTestCase;
use Antarian\Scopes\Calendar\Command\AddCalendar;
use Symfony\Component\Uid\UuidV6;

class AddCalendarTest extends BaseTestCase
{
    private AddCalendar $addCalendar;
    private string $title;
    private ?string $id;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addCalendar = new AddCalendar(
            $this->title = $this->faker->words(asText: true),
            $this->id = $this->faker->optional()->passthrough(UuidV6::generate()),
        );
    }

    public function testAddCalendarHasCorrectData(): void
    {
        $this->assertSame($this->title, $this->addCalendar->title);
        $this->assertSame($this->id, $this->addCalendar->id);
    }
}