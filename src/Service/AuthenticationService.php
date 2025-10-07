<?php

namespace Samuelpouzet\RestfulAuth\Service;


use Laminas\Mvc\MvcEvent;
use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTypeEnum;
use Samuelpouzet\RestfulAuth\Response\AuthResponse;
use Samuelpouzet\RestfulAuth\Response\JwtResponse;

class AuthenticationService
{

    protected const string ALLOWED_FOR_ALL = '*';
    protected array $config;
    protected string|array|null $filter;

    public function __construct(
        protected JWTService $jwtService,
        protected AccountService $accountService,
        protected  $response = new AuthResponse()
    )
    {
    }

    public function getResponse(): AuthResponse
    {
        return $this->response;
    }

    public function authenticate(array $config, MvcEvent $event): void
    {
        $this->config = $config['authentication'];


        if ($this->config['default'] === AuthTypeEnum::permissive) {
            $this->authenticatePermissive($event);
        } elseif ($this->config['default'] === AuthTypeEnum::restrictive) {
            $this->authenticateRestrictive($event);
        } else {
            throw new \Exception('Unkonwn permission type, must be in AuthTypeEnum::permissive, AuthTypeEnum::restrictive');
        }
    }

    protected function authenticatePermissive(MvcEvent $event): void
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $method = $routeMatch->getParam('action');

        $this->filter = $this->config['access_filter'][$controller][$method] ?? null;

        if (!$this->filter || $this->filter === static::ALLOWED_FOR_ALL) {
            $this->response->setStatusCode(AuthResponseCodeEnum::OK);
            return;
        }
        $this->authenticateBoth($event);
    }

    protected function authenticateRestrictive(MvcEvent $event): void
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $method = $routeMatch->getParam('action');

        $this->filter = $this->config['access_filters'][$controller][$method] ?? null;

        if (!$this->filter) {
            $this->response->setStatusCode(AuthResponseCodeEnum::DENIED);
            $this->response->setMessage('config missing');
            return;
        }

        if ($this->filter === static::ALLOWED_FOR_ALL) {
            $this->response->setStatusCode(AuthResponseCodeEnum::OK);
            return;
        }

        $this->authenticateBoth($event);
    }

    protected function authenticateBoth(MvcEvent $event): void
    {
        $token = $event->getRequest()->getHeaders()->get('Authorization');

        if (!$token) {
            $this->response->setStatusCode(AuthResponseCodeEnum::NEEDS_AUTH);
            $this->response->setMessage('token missing');
            return;
        }
        $token = trim(str_ireplace('Authorization: Bearer ', '', $token->toString()));
        $token = $this->jwtService->decrypt($token);
        $validation = $this->jwtService->validateToken($token);

        switch ($validation->getStatusCode()) {
            case JwtResponse::STATUS_OK:
                $this->accountService->setAccountFromToken($token);
                $this->response->setStatusCode(AuthResponseCodeEnum::OK);
                break;
            case JwtResponse::STATUS_EXPIRED:
                $this->response->setStatusCode(AuthResponseCodeEnum::NEEDS_AUTH);
                break;
            default:
                $this->response->setStatusCode(AuthResponseCodeEnum::DENIED);
        }
    }
}
