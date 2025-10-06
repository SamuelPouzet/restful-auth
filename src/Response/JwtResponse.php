<?php

namespace Samuelpouzet\RestfulAuth\Response;

use Laminas\Http\Response;

class JwtResponse
{
    public const STATUS_KO = Response::STATUS_CODE_403;
    public const STATUS_OK = Response::STATUS_CODE_200;
    public const STATUS_EXPIRED = Response::STATUS_CODE_401;
    public const STATUS_ERROR = Response::STATUS_CODE_500;

    protected string $message;
    protected int $statusCode;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }



}
