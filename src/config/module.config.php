<?php

namespace Samuelpouzet\RestfulAuth;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Router\Http\Literal;
use Samuelpouzet\RestfulAuth\Adapter\AuthAdapter;
use Samuelpouzet\RestfulAuth\Adapter\Factory\AuthAdapterFactory;
use Samuelpouzet\RestfulAuth\Controller\Factory\LoginControllerFactory;
use Samuelpouzet\RestfulAuth\Controller\Factory\RefreshControllerFactory;
use Samuelpouzet\RestfulAuth\Controller\LoginController;
use Samuelpouzet\RestfulAuth\Controller\RefreshController;
use Samuelpouzet\RestfulAuth\Entity\User;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTypeEnum;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;
use Samuelpouzet\RestfulAuth\Listener\Factory\RouteListenerFactory;
use Samuelpouzet\RestfulAuth\Listener\RouteListener;
use Samuelpouzet\RestfulAuth\Manager\Factory\JWTManagerFactory;
use Samuelpouzet\RestfulAuth\Manager\JWTManager;
use Samuelpouzet\RestfulAuth\Service\AccountService;
use Samuelpouzet\RestfulAuth\Service\Application;
use Samuelpouzet\RestfulAuth\Service\AuthenticationService;
use Samuelpouzet\RestfulAuth\Service\Factory\AccountServiceFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\ApplicationFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\AuthenticationServiceFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\IdentificationServiceFactory;
use Samuelpouzet\RestfulAuth\Service\Factory\JWTServiceFactory;
use Samuelpouzet\RestfulAuth\Service\IdentificationService;
use Samuelpouzet\RestfulAuth\Service\JWTService;
use Samuelpouzet\RestfulAuth\Strategy\Factory\ExceptionStrategyFactory;

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
            ],
            'refresh' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/refresh',
                    'defaults' => [
                        'controller' => RefreshController::class,
                    ],
                ],
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            LoginController::class => LoginControllerFactory::class,
            RefreshController::class => RefreshControllerFactory::class,
        ]
    ],
    'authentication' => [
        'default' => AuthTypeEnum::restrictive,
        'access_filters' => [

        ]
    ],
    'samuelpouzet' => [
        'jwt' => [
            'issuedBy' => 'https://liberi_fatali.testapi.sam',
            'permittedFor' => 'http://testapi.sam',
            'relatedTo' => 'authentication',
            'identifiedBy' => '1f5cb52ca',
            'exp' => '+20 minutes',
            'exp_refresh' => '+2 months',
        ]
    ],
    'service_manager' => [
        'factories' => [
            //Adapters
            AuthAdapter::class => AuthAdapterFactory::class,
            //Listeners
            RouteListener::class => RouteListenerFactory::class,
            // Managers
            JWTManager::class => JWTManagerFactory::class,
            //Override listeners
            'HttpExceptionStrategy' => ExceptionStrategyFactory::class,
            //Services
            AccountService::class => AccountServiceFactory::class,
            Application::class => ApplicationFactory::class,
            AuthenticationService::class => AuthenticationServiceFactory::class,
            IdentificationService::class => IdentificationServiceFactory::class,
            JWTService::class => JWTServiceFactory::class,
        ],
        'aliases' => [
            'Application' => Application::class,
            'AuthAdapter' => AuthAdapter::class,
            'AuthenticationService' => AuthenticationService::class,
            'IdentificationService' => IdentificationService::class,
            'JWTService' => JWTService::class,
            'JWTManager' => JWTManager::class,
            'RouteListener' => RouteListener::class,
        ]
    ],
    'doctrine' => [
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    UserInterface::class => User::class,
                ]
            ],
        ],
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [dirname(__DIR__) . '/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ],
];
