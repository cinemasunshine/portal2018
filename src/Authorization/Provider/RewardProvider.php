<?php

declare(strict_types=1);

namespace App\Authorization\Provider;

use App\Authorization\Token\AuthorizationCodeToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericProvider;

class RewardProvider
{
    private string $baseEndpoint;
    private string $clientId;
    private AbstractProvider $baseProvider;
    private string $logoutUrl;

    /**
     * @param string[] $scopes
     */
    public function __construct(
        string $host,
        string $clientId,
        string $clientSecret,
        array $scopes,
        string $loginUrl,
        string $logoutUrl
    ) {
        $this->baseEndpoint = 'https://' . $host;
        $this->clientId     = $clientId;

        $this->baseProvider = new GenericProvider([
            'pkceMethod' => GenericProvider::PKCE_METHOD_S256,
            'clientId' => $this->clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $loginUrl,
            'urlAuthorize' => $this->baseEndpoint . '/authorize',
            'urlAccessToken' => $this->baseEndpoint . '/token',
            'urlResourceOwnerDetails' => $this->baseEndpoint . '/unused',
            'scopes' => $scopes,
            'scopeSeparator' => ' ',
        ]);

        $this->logoutUrl = $logoutUrl;
    }

    public function getAuthorizationUrl(string $state): string
    {
        return $this->baseProvider->getAuthorizationUrl(['state' => $state]);
    }

    public function getPkceCode(): ?string
    {
        return $this->baseProvider->getPkceCode();
    }

    public function requestAuthorizationCodeToken(
        string $code,
        string $pkceCode
    ): AuthorizationCodeToken {
        $this->baseProvider->setPkceCode($pkceCode);

        $accessToken = $this->baseProvider->getAccessToken(
            'authorization_code',
            ['code' => $code]
        );

        return AuthorizationCodeToken::create($accessToken);
    }

    public function getLogoutUrl(): string
    {
        $params = [
            'client_id'  => $this->clientId,
            'logout_uri' => $this->logoutUrl,
        ];

        $base = $this->baseEndpoint . '/logout';

        return $base . '?' . http_build_query($params);
    }
}
