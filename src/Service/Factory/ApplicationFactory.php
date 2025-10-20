<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Interop\Container\Containerinterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Samuelpouzet\RestfulAuth\Service\Application;

class ApplicationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Application
    {
        return new Application(
            $container,
            $container->get('EventManager'),
            $container->get('Request'),
            $container->get('Response')
        );
    }
}
