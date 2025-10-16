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
use Samuelpouzet\RestfulAuth\Manager\JWTManager;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class RefreshController extends AbstractRestfulController implements LoginInterface
{

    public function __construct(
        protected JwtService             $jwtService,
        protected EntityManagerInterface $entityManager,
        protected JWTManager             $jwtManager
    )
    {
    }

    public function postAction(): JsonModel
    {
        $token = $this->getRequest()->getHeader('refresh-token');

        if (!$token instanceof GenericHeader) {
            return $this->restFullError(Response::STATUS_CODE_403, 'no token');
        }
        $oldToken = $token->getFieldValue();
        if (! $this->jwtManager->checkToken($oldToken)) {
            return $this->restFullError(Response::STATUS_CODE_403, 'deleted or expired token');
        }

        $decryptedToken = $this->jwtService->decrypt($oldToken);
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
            $now = new \DateTimeImmutable();
            $token = $this->jwtService->encodeUser($now, $user);
            $refresh = $this->jwtService->encodeUser($now, $user, AuthTokenTypeEnum::TYPE_REFRESH);
            $this->jwtManager->updateToken($oldToken, $refresh, $now);
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
