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
    /** @var string */
    protected $name = 'client_credentials';

    /** @var string */
    protected $host;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var HttpClient */
    protected $httpClient;

    /**
     * construct
     *
     * @param string $host
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(string $host, string $clientId, string $clientSecret)
    {
        $this->host         = $host;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient   = $this->createHttpClient('https://' . $this->host);
    }

    /**
     * Request token
     *
     * @return Token
     */
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
