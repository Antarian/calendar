<?php
declare(strict_types=1);
namespace Antarian\Core\Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected Generator $faker;
    protected function setUp(): void
    {
        $this->faker = Factory::create();
        parent::setUp();
    }
}