<?php

namespace Samuelpouzet\RestfulAuth\Listener;

use Laminas\Http\Response;
use Samuelpouzet\RestfulAuth\Service\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use \Samuelpouzet\Restful\Listener\RouteListener as parentListener;
use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
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

        if ($routeMatch instanceof RouteMatch) {
            //on récupère la méthode;
            $this->getAction($routeMatch);
            $event->setRouteMatch($routeMatch);

            //on check les droits d'accès
            $this->authenticationService->authenticate($event);
            $result = $this->authenticationService->getResponse();
            if ($result->getStatusCode() !== AuthResponseCodeEnum::OK) {
                $this->dispatchError(
                    $event,
                    MvcEvent::EVENT_DISPATCH_ERROR,
                    Application::ERROR_NOT_AUTHORIZED,
                    Response::STATUS_CODE_403
                );

                return $event->getParams();
            }
            return $routeMatch;
        }

        $this->dispatchError(
            $event,
            MvcEvent::EVENT_DISPATCH_ERROR,
            Application::ERROR_ROUTER_NO_MATCH,
            Response::STATUS_CODE_404
        );

        return $event->getParams();
    }

    protected function dispatchError(MvcEvent $event, string $name, string $error, int $responseCode)
    {

        $event->setName($name);
        $event->setError($error);
        $event->getResponse()->setStatusCode($responseCode);

        $target = $event->getTarget();
        $results = $target->getEventManager()->triggerEvent($event);
        if (!empty($results)) {
            return $results->last();
        }
        return null;
    }
}
