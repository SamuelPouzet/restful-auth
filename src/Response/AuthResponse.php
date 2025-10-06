<?php

namespace Samuelpouzet\RestfulAuth\Response;

use Samuelpouzet\RestfulAuth\Interface\UserInterface;

class AuthResponse
{
    public const CODE_SUCCESS = 1;
    public const CODE_FAILURE = 0;

    private int $status = self::CODE_SUCCESS;

    private string $message = 'Allowed';

    private UserInterface $user;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): AuthResponse
    {
        $this->status = $status;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): AuthResponse
    {
        $this->message = $message;
        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): AuthResponse
    {
        $this->user = $user;
        return $this;
    }
}
