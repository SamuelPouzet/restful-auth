<?php

namespace Samuelpouzet\RestfulAuth\Controller;

use Laminas\View\Model\JsonModel;
use Samuelpouzet\Restful\Controller\AbstractRestfulController;
use Samuelpouzet\RestfulAuth\Enumerations\AuthResponseCodeEnum;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTokenTypeEnum;
use Samuelpouzet\RestfulAuth\Interface\LoginInterface;
use Samuelpouzet\RestfulAuth\Manager\JWTManager;
use Samuelpouzet\RestfulAuth\Service\IdentificationService;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class LoginController extends AbstractRestfulController implements LoginInterface
{
    public function __construct(
        protected IdentificationService $identificationService,
        protected JWTService $jwtService,
        protected JWTManager $jwtManager
    ) {
    }
    public function postAction(): JsonModel
    {
        $credentials = $this->getRequestData();
        $response = $this->identificationService->login($credentials);

        if ($response->getStatusCode() === AuthResponseCodeEnum::OK) {
            //création du token de connexion
            $now = new \DateTimeImmutable();
            $token = $this->jwtService->encodeUser($now, $response->getUser());
            $refresh = $this->jwtService->encodeUser($now, $response->getUser(), AuthTokenTypeEnum::TYPE_REFRESH);
            $this->jwtManager->saveToken($refresh, $now);
            return new JsonModel([
                    "status" => "success",
                    'token' => $token,
                    'refresh_token' => $refresh,
                    // todo user response
                    'user' => $response->getUser()->getLogin()
                ]
            );
        }

        return new JsonModel(['KO' => $response->getMessage()]);
    }
}
