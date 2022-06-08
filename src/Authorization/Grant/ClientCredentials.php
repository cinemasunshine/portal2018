<?php

declare(strict_types=1);

namespace App\Authorization\Grant;

use App\Authorization\Token\ClientCredentialsToken as Token;
use GuzzleHttp\Client as HttpClient;

/**
 * Client Credentials Grant class
 */
class ClientCredentials extends AbstractGrant
{
    protected string $name = 'client_credentials';

    protected string $host;

    protected string $clientId;

    protected string $clientSecret;

    protected HttpClient $httpClient;

    public function __construct(string $host, string $clientId, string $clientSecret)
    {
        $this->host         = $host;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient   = $this->createHttpClient('https://' . $this->host);
    }

    public function requestToken(): Token
    {
        $headers = $this->getRequestHeaders($this->clientId, $this->clientSecret);

        $params = [
            'grant_type' => $this->name,
        ];

        $response = $this->httpClient->post('/oauth2/token', [
            'headers' => $headers,
            'form_params' => $params,
        ]);

        $rawContents = $response->getBody()->getContents();

        return Token::create(json_decode($rawContents, true));
    }
}
