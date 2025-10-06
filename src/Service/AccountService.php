<?php

namespace Samuelpouzet\RestfulAuth\Service;

use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Token\Plain;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;

class AccountService
{
    protected ?UserInterface $account;

    public function __construct(protected EntityManagerInterface $entityManager)
    {

    }

    public function getAccount(): UserInterface
    {
        if($this->account) {
            return $this->account;
        }
        throw new \Exception('No user connected');
    }

    public function setAccountFromToken(Plain $token): void
    {
        $this->account = $this->entityManager->getRepository(UserInterface::class)->findOneBy([
            'login' => $token->claims()->get('login')
        ]);
    }
}
