<?php

namespace Samuelpouzet\RestfulAuth\Service\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Psr\Container\ContainerInterface;
use Samuelpouzet\RestfulAuth\Service\JWTService;

class JWTServiceFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): JWTService
    {
        $config = $container->get('config')['samuelpouzet']['jwt'] ?? null;
        if(! $config) {
            throw new ServiceNotCreatedException('JWT config missing');
        }
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::plainText(random_bytes(32));
        return new JwtService($config, $tokenBuilder, $algorithm, $signingKey);
    }
}
