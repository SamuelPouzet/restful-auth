<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Service\AccountService;

class AccountServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccountService
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AccountService($entityManager);
    }
}
