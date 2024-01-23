<?php
declare(strict_types=1);
namespace Tests\Integration;

use DI\Container;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppTestCase extends TestCase
{
    protected ContainerInterface $container;
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->container->set(
            ValidatorInterface::class,
            function () {
                $validatorBuilder = Validation::createValidatorBuilder();
                $validatorBuilder->enableAttributeMapping();

                return $validatorBuilder->getValidator();
            }
        );

        $this->faker = Factory::create();
    }
}