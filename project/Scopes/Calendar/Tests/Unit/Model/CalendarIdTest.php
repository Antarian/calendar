<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Model;

use Antarian\Core\Tests\BaseTestCase;
use Antarian\Scopes\Calendar\Model\CalendarId;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

class CalendarIdTest extends BaseTestCase
{
    public function testCalendarIdInterface()
    {
        $this->assertInstanceOf(Uuid::class, new CalendarId(UuidV6::generate()));
    }
}