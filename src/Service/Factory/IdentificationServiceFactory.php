<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Service\IdentificationService;

class IdentificationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $authAdapter = $container->get('AuthAdapter');
        return new IdentificationService($authAdapter);
    }
}
