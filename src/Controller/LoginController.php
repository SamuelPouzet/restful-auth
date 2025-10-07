<?php

namespace Samuelpouzet\RestfulAuth\Controller;

use Laminas\View\Model\JsonModel;
use Samuelpouzet\Restful\Controller\AbstractRestfulController;
use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
use Samuelpouzet\RestfulAuth\Interface\LoginInterface;
use Samuelpouzet\RestfulAuth\Service\IdentificationService;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class LoginController extends AbstractRestfulController implements LoginInterface
{
    public function __construct(
        protected IdentificationService $identificationService,
        protected JWTService $jwtService
    ) {
    }
    public function postAction(): JsonModel
    {
        $credentials = $this->getRequestData();
        $response = $this->identificationService->login($credentials);

        if ($response->getStatusCode() === AuthResponseCodeEnum::OK) {
            //création du token de connexion
            $token = $this->jwtService->encodeUser($response->getUser());
            return new JsonModel([
                    "status" => "success",
                    'token' => $token]
            );
        }

        return new JsonModel(['KO' => $response->getMessage()]);
    }
}
