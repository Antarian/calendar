<?php
declare(strict_types=1);

use Antarian\Calendar\Service\AvailabilityVerifier;
use PHPUnit\Framework\TestCase;

class AvailabilityVerifierTest extends TestCase
{


    public function testTimeSlotIsAvailable(): void
    {
        $availabilityVerifier = new AvailabilityVerifier();
        $result = $availabilityVerifier->isSlotAvailable();

        $this->assertTrue($result);
    }
}