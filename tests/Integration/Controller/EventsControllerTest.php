<?php
declare(strict_types=1);
namespace Tests\Integration\Controller;

use Antarian\Scopes\Calendar\Service\AvailabilityService;
use App\Controller\EventsController;
use App\Exception\ValidationException;
use App\Repository\CacheCalendarRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Integration\AppTestCase;

class EventsControllerTest extends AppTestCase
{
    private EventsController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = $this->createMock(CacheCalendarRepository::class);
        $availabilityService = $this->createMock(AvailabilityService::class);

        $this->controller = new EventsController(
            $repository,
            $availabilityService,
            $this->container->get(ValidatorInterface::class),
        );
    }

    public function testAddCalendarWithIncorrectData()
    {
        $id = $this->faker->uuid();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Violation \'This value should not be blank.\' for the property \'title\' with the value \'\', ' .
            'Violation \'This is not a valid UUID.\' for the property \'id\' with the value \''.$id.'\''
        );

        $this->controller->addCalendar('', $id);
    }

    public function testAddEventWithIncorrectData()
    {
        $id = $this->faker->uuid();
        $startDateTime = $this->faker->date();
        $endDateTime = $this->faker->date('Y-m-d H:i:s');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Violation \'This is not a valid UUID.\' for the property \'calendarId\' with the value \''.$id.'\', ' .
            'Violation \'This value should not be blank.\' for the property \'title\' with the value \'\', ' .
            'Violation \'This value is not a valid datetime.\' for the property \'startDateTime\' with the value \''.$startDateTime.'\', ' .
            'Violation \'This value is not a valid datetime.\' for the property \'endDateTime\' with the value \''.$endDateTime.'\''
        );

        $this->controller->addEvent('', $id, $startDateTime, $endDateTime);
    }
}