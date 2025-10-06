<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class JWTServiceFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): JWTService
    {
        $config = $container->get('config')['JWT'] ?? null;
        if(! $config) {
            throw new ServiceNotCreatedException('JWT config missing');
        }
        return new JWTService($config);
    }
}
