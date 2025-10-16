<?php

namespace Samuelpouzet\RestfulAuth\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Samuelpouzet\RestfulAuth\Entity\Token;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTokenTypeEnum;

class JWTManager
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected array $config = [],
    ) {

    }

    public function saveToken(string $token, \DateTimeImmutable $createdAt): Token
    {
        $expiration = $this->config['exp_refresh'];

        $token = (new Token())
            ->setToken($token)
            ->setExpiration($createdAt->modify($expiration))
        ;

        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return $token;
    }

    public function checkToken(string $token): bool
    {
        $check = $this->entityManager->getRepository(Token::class)->findOneBy(['token' => $token]);
        if (! $check) {
            return false;
        }

        $now = new \DateTimeImmutable();
        $expiration = $check->getExpiration();
        if ($now > $expiration) {
            return false;
        }
        return true;
    }

    public function updateToken(string $oldToken, $newToken, \DateTimeImmutable $createdAt): void
    {
        $expiration = $this->config['exp_refresh'];
        $toUpdate = $this->entityManager->getRepository(Token::class)->findOneBy(['token' => $oldToken]);
        if (! $toUpdate) {
            throw new \Exception('token not found');
        }
        $toUpdate
            ->setToken($newToken)
            ->setExpiration($createdAt->modify($expiration))
        ;
        $this->entityManager->flush();
    }
}
