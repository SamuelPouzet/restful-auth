<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Service\AccountService;
use Samuelpouzet\RestfulAuth\Service\AuthenticationService;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthenticationService
    {
        $jwtService = $container->get(JWTService::class);
        $accountService = $container->get(AccountService::class);
        return new AuthenticationService($jwtService, $accountService);
    }
}
