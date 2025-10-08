<?php

namespace Samuelpouzet\RestfulAuth\Interface;

use DateTimeImmutable;

interface UserInterface
{
    public function getArrayCopy(): array;
    public function getId(): int;
    public function setId(int $id): static;
    public function getLogin(): string;
    public function setLogin(string $login): static;
    public function getPassword(): string;
    public function setPassword(string $password): static;

    public function getCreatedAt(): DateTimeImmutable;
    public function setCreatedAt(DateTimeImmutable $createdAt): static;

}
