<?php
declare(strict_types=1);
namespace Antarian\Scopes\Calendar\Tests\Unit\Model;

use Antarian\Scopes\Calendar\Model\CalendarId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CalendarIdTest extends TestCase
{
    public const DUMMY_UUID_V6 = '1eeb911f-9e67-6f44-9be1-6d2d47d8129e';

    public function testCalendarIdInterface()
    {
        $this->assertInstanceOf(Uuid::class, new CalendarId(self::DUMMY_UUID_V6));
    }
}