<?php

namespace Samuelpouzet\RestfulAuth\Listener\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Listener\RouteListener;

class RouteListenerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RouteListener
    {
        $authenticationService = $container->get('AuthenticationService');
        return new RouteListener($authenticationService);
    }

}
