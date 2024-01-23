<?php
declare(strict_types=1);
namespace App\DI;

use Antarian\Scopes\Calendar\Repository\CalendarRepository;
use Antarian\Scopes\Calendar\Service\AvailabilityService;
use App\Repository\CacheCalendarRepository;
use App\Service\AvailabilityVerifier;
use DI\Container;
use DI\Definition\Source\MutableDefinitionSource;
use DI\Proxy\ProxyFactory;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AppContainer extends Container
{
    public function __construct(MutableDefinitionSource|array $definitions = [], ProxyFactory $proxyFactory = null, ContainerInterface $wrapperContainer = null)
    {
        $definitions = $this->getDefinitions();
        parent::__construct($definitions, $proxyFactory, $wrapperContainer);
    }

    private function getDefinitions(): array
    {
        return [
            FilesystemAdapter::class => function () {
                return new FilesystemAdapter('calendar');
            },
            CalendarRepository::class => function (Container $c) {
                return new CacheCalendarRepository($c->get(FilesystemAdapter::class));
            },
            AvailabilityService::class => function (Container $c) {
                return new AvailabilityVerifier($c->get(CalendarRepository::class));
            },
        ];
    }
}