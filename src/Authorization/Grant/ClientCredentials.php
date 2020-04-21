<?php

/**
 * ClientCredentials.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Grant;

use GuzzleHttp\Client as HttpClient;
use Cinemasunshine\Portal\Authorization\Token\ClientCredentialsToken as Token;

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
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $baseUri = 'https://' . $this->host;
        $this->httpClient = $this->createHttpClient($baseUri);
    }

    /**
     * Request token
     *
     * @return Token
     */
    public function requestToken(): Token
    {
        $endpoint = '/oauth2/token';
        $headers = $this->getRequestHeaders($this->clientId, $this->clientSecret);
        $params = [
            'grant_type' => $this->name,
        ];

        $response = $this->httpClient->post($endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]);

        $rawContents = $response->getBody()->getContents();

        return Token::create(json_decode($rawContents, true));
    }
}
