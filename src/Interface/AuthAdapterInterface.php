<?php

namespace Samuelpouzet\RestfulAuth\Interface;


use Samuelpouzet\RestfulAuth\Response\AuthResponse;

interface AuthAdapterInterface
{
    public function getLogin(): string;
    public function setLogin(string $login): static;

    public function getPassword(): string;

    public function setPassword(string $password): static;

    public function authenticate(): AuthResponse;
}
