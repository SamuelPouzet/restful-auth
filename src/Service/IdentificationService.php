<?php

namespace Samuelpouzet\RestfulAuth\Service;

use Samuelpouzet\RestfulAuth\Interface\AuthAdapterInterface;
use Samuelpouzet\RestfulAuth\Response\AuthResponse;

class IdentificationService
{
    public function __construct(protected AuthAdapterInterface $authAdapter)
    {

    }

    public function login(array $credentials): AuthResponse
    {
        return $this->authAdapter
            ->setLogin($credentials['login'])
            ->setPassword($credentials['password'])
            ->authenticate();
    }
}
