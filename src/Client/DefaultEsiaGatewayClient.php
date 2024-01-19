<?php
/**
 * ESIA Gateway Client
 *
 * @author Valentin Nazarov <v.nazarov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\EsiaGateway\Client;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface as HttpClient;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface as Serializer;
use Vanta\Integration\EsiaGateway\Infrastructure\HttpClient\ConfigurationClient;
use Vanta\Integration\EsiaGateway\Struct\UserInfo;
use Yiisoft\Http\Method;

final class DefaultEsiaGatewayClient implements EsiaGatewayClient
{
    private Serializer $serializer;

    private HttpClient $client;

    private ConfigurationClient $configuration;

    public function __construct(Serializer $serializer, HttpClient $client, ConfigurationClient $configuration)
    {
        $this->serializer    = $serializer;
        $this->client        = $client;
        $this->configuration = $configuration;
    }

    public function createAuthorizationUrlBuilder(): AuthorizationUrlBuilder
    {
        return new AuthorizationUrlBuilder(
            $this->configuration->getUrl(),
            $this->configuration->getClientId(),
            $this->configuration->getRedirectUri(),
        );
    }

    public function getPairKeyByAuthorizationCode(string $code, ?string $redirectUri = null): PairKey
    {
        $queryParams = http_build_query([
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $redirectUri ?? $this->configuration->getRedirectUri(),
            'client_id'     => $this->configuration->getClientId(),
            'code'          => $code,
            'client_secret' => $this->configuration->getClientSecret(),
        ]);

        $request = new Request(Method::POST, sprintf('/auth/token?%s', $queryParams));
        $content = $this->client->sendRequest($request)->getBody()->__toString();

        return $this->serializer->deserialize($content, PairKey::class, 'json');
    }

    public function getPairKeyByRefreshToken(string $refreshToken, ?string $redirectUri = null): PairKey
    {
        $queryParams = http_build_query([
            'grant_type'    => 'refresh_token',
            'redirect_uri'  => $redirectUri ?? $this->configuration->getRedirectUri(),
            'client_id'     => $this->configuration->getClientId(),
            'refresh_token' => $refreshToken,
            'client_secret' => $this->configuration->getClientSecret(),
        ]);

        $request = new Request(Method::POST, sprintf('/auth/token?%s', $queryParams));
        $content = $this->client->sendRequest($request)->getBody()->__toString();

        return $this->serializer->deserialize($content, PairKey::class, 'json');
    }

    public function getUserInfo(string $accessToken): UserInfo
    {
        $request = new Request(
            Method::POST,
            '/auth/userinfo',
            ['Authorization' => sprintf('Bearer %s', $accessToken)],
        );

        $response = $this->client->sendRequest($request);
        $content  = $response->getBody()->__toString();

        return $this->serializer->deserialize($content, UserInfo::class, 'json', [
            UnwrappingDenormalizer::UNWRAP_PATH => '[info]',
        ]);
    }
}
