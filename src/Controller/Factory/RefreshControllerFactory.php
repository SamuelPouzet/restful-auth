<?php

namespace Samuelpouzet\RestfulAuth\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Controller\RefreshController;
use Samuelpouzet\RestfulAuth\Manager\JWTManager;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class RefreshControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RefreshController
    {
        $JwtService = $container->get(JwtService::class);
        $entityManager = $container->get(EntityManager::class);
        $jwtManager = $container->get(JwtManager::class);
        return new RefreshController($JwtService, $entityManager, $jwtManager);
    }
}
