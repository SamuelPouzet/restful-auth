<?php

namespace Samuelpouzet\RestfulAuth\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Crypt\Crypt;
use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
use Samuelpouzet\RestfulAuth\Interface\AuthAdapterInterface;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;
use Samuelpouzet\RestfulAuth\Response\AuthResponse;

class AuthAdapter implements AuthAdapterInterface
{
    private string $login;
    private string $password;

    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }


    public function authenticate(): AuthResponse
    {
        $response = new AuthResponse();

        $user = $this->entityManager
            ->getRepository(UserInterface::class)
            ->findOneBy(['login' => $this->getLogin()]);
        ;

        if (!$user) {
            return $response
                ->setMessage('invalid login')
                ->setStatusCode(AuthResponseCodeEnum::DENIED)
                ;
        }

        $crypt = new Crypt();

        if (!$crypt->verify($this->getPassword(), $user->getPassword()) ) {
            return $response
                ->setMessage('invalid password')
                ->setStatusCode(AuthResponseCodeEnum::DENIED)
                ;
        }
        $response->setUser($user);
        return $response;
    }

}
