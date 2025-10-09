<?php

namespace Samuelpouzet\RestfulAuth\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Http\Header\ContentType;
use Laminas\Http\Header\GenericHeader;
use Laminas\Http\Response;
use Samuelpouzet\Restful\Controller\AbstractRestfulController;
use Samuelpouzet\RestfulAuth\Enumerations\AuthTokenTypeEnum;
use Samuelpouzet\RestfulAuth\Interface\LoginInterface;
use \Laminas\View\Model\JsonModel;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class RefreshController extends AbstractRestfulController implements LoginInterface
{

    public function __construct(
        protected JwtService             $jwtService,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function postAction(): JsonModel
    {
        $token = $this->getRequest()->getHeader('refresh-token');

        if (!$token instanceof GenericHeader) {
            return $this->restFullError(Response::STATUS_CODE_403, 'no token');
        }
        $decryptedToken = $this->jwtService->decrypt($token->getFieldValue());
        if (!$decryptedToken) {
            return $this->restFullError(Response::STATUS_CODE_403, 'malformed token');
        }

        $response = $this->jwtService->validateToken($decryptedToken);
        if ($response->getStatusCode() === Response::STATUS_CODE_200) {
            $login = $decryptedToken->claims()->get('login');
            $user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);
            if (!$user) {
                return $this->restFullError(Response::STATUS_CODE_403, 'invalid user');
            }

            $token = $this->jwtService->encodeUser($user);
            $refresh = $this->jwtService->encodeUser($user, AuthTokenTypeEnum::TYPE_REFRESH);
            return new JsonModel([
                    "status" => "success",
                    'token' => $token,
                    'refresh_token' => $refresh,
                    // todo user response
                ]
            );
        }
        return $this->restFullError(Response::STATUS_CODE_403, 'invalid token');
    }
}
