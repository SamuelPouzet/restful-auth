<?php

namespace Samuelpouzet\RestfulAuth\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sp_refresh_token')]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'datetime_immutable', length: 255)]
    private DateTimeImmutable $expiration;

    public function getExpiration(): DateTimeImmutable
    {
        return $this->expiration;
    }

    public function setExpiration(DateTimeImmutable $expiration): static
    {
        $this->expiration = $expiration;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
}
