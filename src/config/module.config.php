<?php

namespace Samuelpouzet\RestfulAuth\Strategy\Enumerations;


use SamuelPouzet\RestfulAuth\Enumerations\AuthTypeEnum;


return [
    'authentication' => [
        'default' => AuthTypeEnum::restrictive,
        'access_filters' => [
            AuthenticateController::class => [
                'get' => '*',
                'getall' => '@',
                'post' => '@'
            ]
        ]
    ],
    'JWT' => [
        'signing_key' => 'Jenaimarredecessecuritesdemesdeuxetlàjedevraisdepasserallegrementlenombredecaracteres',
        'header' => [

            'iss' => 'http://www.example.com',
            'sub' => 'component1',
            'aud' => 'localhost',
            'jti' => '8AFH567FF9956',
        ],
    ],
    'service_manager' => [
        'factories' => [
            //needs a PR to clean on parent
            AccountService::class => AccountServiceFactory::class,
            RouteListener::class => RouteListenerFactory::class,
            AuthenticationService::class => AuthenticationServiceFactory::class,
            JWTService::class => JWTServiceFactory::class,
        ],
        'aliases' => [
            'AuthenticationService' => AuthenticationService::class,
            'JWTService' => JWTService::class,
            'RouteListener' => RouteListener::class,
        ]
    ],
];
