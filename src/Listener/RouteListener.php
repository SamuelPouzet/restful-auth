<?php

namespace Samuelpouzet\RestfulAuth\Listener;

use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use \Samuelpouzet\Restful\Listener\RouteListener as parentListener;
use \Samuelpouzet\RestfulAuth\Service\AuthenticationService;

class RouteListener extends parentListener
{
    public function __construct(protected AuthenticationService $authenticationService)
    {
    }

    public function onRoute(MvcEvent $event)
    {
        $request = $event->getRequest();
        $router = $event->getRouter();
        $routeMatch = $router->match($request);
        $config = $event->getApplication()->getServiceManager()->get('config');

        if ($routeMatch instanceof RouteMatch) {
            //on récupère la méthode;
            $this->getAction($routeMatch);
            $event->setRouteMatch($routeMatch);

            //on check les droits d'accès
            $this->authenticationService->authenticate($config, $event);
            return $routeMatch;
        }

        $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $event->setError(Application::ERROR_ROUTER_NO_MATCH);

        $target = $event->getTarget();
        $results = $target->getEventManager()->triggerEvent($event);
        if (!empty($results)) {
            return $results->last();
        }

        return $event->getParams();
    }
}
