<?php

namespace Samuelpouzet\RestfulAuth\Strategy\Enumerations;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Router\Http\Literal;
use Samuelpouzet\RestfulAuth\Controller\Factory\LoginControllerFactory;
use Samuelpouzet\RestfulAuth\Controller\LoginController;
use Samuelpouzet\RestfulAuth\Entity\User;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTypeEnum;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;
use Samuelpouzet\RestfulAuth\Listener\Factory\RouteListenerFactory;
use Samuelpouzet\RestfulAuth\Listener\RouteListener;
use Samuelpouzet\RestfulAuth\Service\AccountService;
use Samuelpouzet\RestfulAuth\Service\AuthenticationService;
use Samuelpouzet\RestfulAuth\Service\Factory\AccountServiceFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\AuthenticationServiceFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\JWTServiceFactory;
use Samuelpouzet\RestfulAuth\Service\JWTService;


return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => LoginController::class,
                    ],
                ],
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            LoginController::class => LoginControllerFactory::class,
        ]
    ],
    'authentication' => [
        'default' => AuthTypeEnum::restrictive,
        'access_filters' => [
//            AuthenticateController::class => [
//                'get' => '*',
//                'getall' => '@',
//                'post' => '@'
//            ]
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
            //Listeners
            RouteListener::class => RouteListenerFactory::class,
            //Services
            AccountService::class => AccountServiceFactory::class,
            AuthenticationService::class => AuthenticationServiceFactory::class,
            JWTService::class => JWTServiceFactory::class,
        ],
        'aliases' => [
            'AuthenticationService' => AuthenticationService::class,
            'JWTService' => JWTService::class,
            'RouteListener' => RouteListener::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'entity_resolver' => [
                UserInterface::class => User::class
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
];
