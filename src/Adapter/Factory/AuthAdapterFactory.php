<?php

namespace Samuelpouzet\RestfulAuth\Adapter\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Adapter\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);
        return new AuthAdapter($entityManager);
    }
}
