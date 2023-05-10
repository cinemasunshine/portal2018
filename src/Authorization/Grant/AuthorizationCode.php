<?php

declare(strict_types=1);

namespace App\Authorization\Grant;

class AuthorizationCode
{
    protected string $name = 'authorization_code';

    protected string $host;

    protected string $clientId;

    protected string $clientSecret;

    public function __construct(string $host, string $clientId, string $clientSecret)
    {
        $this->host         = $host;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getLogoutUrl(string $redirectUri): string
    {
        $params = [
            'client_id'  => $this->clientId,
            'logout_uri' => $redirectUri,
        ];

        $base = 'https://' . $this->host . '/logout';

        return $base . '?' . http_build_query($params);
    }
}
