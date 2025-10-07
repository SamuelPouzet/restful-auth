<?php

namespace Samuelpouzet\RestfulAuth\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;
use Samuelpouzet\RestfulAuth\Interface\UserInterface;
use Samuelpouzet\RestfulAuth\Response\JwtResponse;

class JWTService
{
    public function __construct(
        protected array $config,
        protected Builder $tokenBuilder,
        protected Sha256 $algorithm,
        protected InMemory $signingKey,
        protected $parser = new Parser(new JoseEncoder()),
        protected $validator = new Validator()
    ) {
    }

    public function decrypt(string $encrypted): Plain
    {
        $token = $this->parser->parse($encrypted);
        return $token;
    }

    public function validateToken(Plain $unencrypted): JwtResponse
    {
        try {
            $response = new JwtResponse();

            if (isset($this->config['iss']) && ! $this->validator->validate($unencrypted, new IssuedBy($this->config['iss']))) {
                $response->setStatusCode(JwtResponse::STATUS_KO);
                $response->setMessage('iss failed');
                return $response;
            }

            if (isset($this->config['aud']) && ! $this->validator->validate($unencrypted, new PermittedFor($this->config['aud']))) {
                $response->setStatusCode(JwtResponse::STATUS_KO);
                $response->setMessage('aud failed');
                return $response;
            }

            if (isset($this->config['sub']) && ! $this->validator->validate($unencrypted, new RelatedTo($this->config['sub']))) {
                $response->setStatusCode(JwtResponse::STATUS_KO);
                $response->setMessage('sub failed');
                return $response;
            }

            if (isset($this->config['jti']) && ! $this->validator->validate($unencrypted, new IdentifiedBy($this->config['jti']))) {
                $response->setStatusCode(JwtResponse::STATUS_KO);
                $response->setMessage('jti failed');
                return $response;
            }

            if ($unencrypted->isExpired(new \DateTimeImmutable())) {
                $response->setStatusCode(JwtResponse::STATUS_EXPIRED);
                $response->setMessage('token expired');
                return $response;
            }
            ;
        } catch (RequiredConstraintsViolated $exception) {
            $response->setStatusCode(JwtResponse::STATUS_ERROR);
            return $response;
        }

        $response->setStatusCode(JwtResponse::STATUS_OK);
        return $response;
    }

    public function encodeUser(UserInterface $user): string
    {
        $now = new DateTimeImmutable();
        $token = $this->tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy($this->config['issuedBy'])
            // Configures the audience (aud claim)
            ->permittedFor($this->config['permittedFor'])
            // Configures the subject of the token (sub claim)
            ->relatedTo($this->config['relatedTo'])
            // Configures the id (jti claim)
            ->identifiedBy($this->config['identifiedBy'])
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+20 minutes'))
            // Configures a new claim, called "uid"
            ->withClaim('login', $user->getLogin() )
            // Builds a new token
            ->getToken($this->algorithm, $this->signingKey);
        return $token->toString();
    }
}
