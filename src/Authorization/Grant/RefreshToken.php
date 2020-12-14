<?php

/**
 * RefreshToken.php
 */

declare(strict_types=1);

namespace App\Authorization\Grant;

use App\Authorization\Token\AuthorizationCodeToken as Token;
use GuzzleHttp\Client as HttpClient;

/**
 * Refresh Token Grant class
 */
class RefreshToken extends AbstractGrant
{
    /** @var string */
    protected $name = 'refresh_token';

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
     * @param string $refreshToken
     * @return Token
     */
    public function requestToken(string $refreshToken): Token
    {
        $headers = $this->getRequestHeaders($this->clientId, $this->clientSecret);

        $params = [
            'grant_type'    => $this->name,
            'client_id'     => $this->clientId,
            'refresh_token' => $refreshToken,
        ];

        $response = $this->httpClient->post('/token', [
            'headers' => $headers,
            'form_params' => $params,
        ]);

        $rawContents = $response->getBody()->getContents();

        $data = json_decode($rawContents, true);

        $data['refresh_token'] = $refreshToken;

        return Token::create($data);
    }
}
