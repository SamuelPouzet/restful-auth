<?php

namespace Samuelpouzet\RestfulAuth\Response;

use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;

class AuthResponse
{
    private AuthResponseCodeEnum $status = AuthResponseCodeEnum::OK;

    private string $message = 'Allowed';

    private UserInterface $user;

    public function getStatusCode(): AuthResponseCodeEnum
    {
        return $this->status;
    }

    public function setStatusCode(AuthResponseCodeEnum $status): AuthResponse
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
