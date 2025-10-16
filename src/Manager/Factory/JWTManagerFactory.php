<?php

namespace Samuelpouzet\RestfulAuth\Manager\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Manager\JWTManager;

class JWTManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): JWTManager
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config')['samuelpouzet']['jwt'] ?? null;
        if(! $config) {
            throw new ServiceNotCreatedException('JWT config missing');
        }
        return new JWTManager($entityManager, $config);
    }
}
