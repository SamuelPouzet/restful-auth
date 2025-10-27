<?php

namespace Samuelpouzet\RestfulAuth\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Controller\LoginController;
use Samuelpouzet\RestfulAuth\Manager\JWTManager;
use Samuelpouzet\RestfulAuth\Service\IdentificationService;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class LoginControllerFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LoginController
    {
        $identificationService = $container->get('IdentificationService');
        $jwtService = $container->get(JWTService::class);
        $jwtManager = $container->get(JwtManager::class);
        return new LoginController($identificationService, $jwtService, $jwtManager);
    }
}
