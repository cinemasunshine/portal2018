<?php

declare(strict_types=1);

namespace App\Authorization\Grant;

use App\Authorization\Token\AuthorizationCodeToken as Token;
use GuzzleHttp\Client as HttpClient;

/**
 * Authorization Code Grant class
 */
class AuthorizationCode extends AbstractGrant
{
    /** @var string */
    protected $name = 'authorization_code';

    /** @var string */
    protected $host;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var HttpClient */
    protected $httpClient;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

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
     * return authorization URL
     *
     * @link https://m-p.backlog.jp/view/SASAKI-485
     *
     * @param string      $codeVerifier
     * @param string      $redirectUri
     * @param array       $scope
     * @param string|null $state
     * @return string
     */
    public function getAuthorizationUrl(
        string $codeVerifier,
        string $redirectUri,
        array $scope,
        ?string $state = null
    ): string {
        $codeChallengeMethod = $this->codeChallengeMethod;
        $codeChallenge       = $this->generateCodeChallenge($codeVerifier, $codeChallengeMethod);
        $scope               = $this->generateScopeStr($scope);

        $params = [
            'response_type'         => 'code',
            'client_id'             => $this->clientId,
            'redirect_uri'          => $redirectUri,
            'scope'                 => $scope,
            'code_challenge_method' => $codeChallengeMethod,
            'code_challenge'        => $codeChallenge,
        ];

        if ($state) {
            $params['state'] = $state;
        }

        $base = 'https://' . $this->host . '/authorize';

        return $base . '?' . http_build_query($params);
    }

    /**
     * generate code_challenge
     *
     * @param string $codeVerifier
     * @param string $codeChallengeMethod
     * @return string
     *
     * @throws \LogicException
     */
    protected function generateCodeChallenge(string $codeVerifier, string $codeChallengeMethod): string
    {
        if ($codeChallengeMethod === 'S256') {
            return base64_encode(hash('sha256', $codeVerifier));
        }

        throw new \LogicException(sprintf('code_challenge_method does not support %s.', $codeChallengeMethod));
    }

    /**
     * generate scope string
     *
     * @param array $scopeList
     * @return string
     */
    protected function generateScopeStr(array $scopeList): string
    {
        return implode(' ', $scopeList);
    }

    /**
     * Request token
     *
     * @param string $code
     * @param string $redirectUri
     * @param string $codeVerifier
     * @return Token
     */
    public function requestToken(string $code, string $redirectUri, string $codeVerifier): Token
    {
        $headers = $this->getRequestHeaders($this->clientId, $this->clientSecret);

        $params = [
            'grant_type'    => $this->name,
            'client_id'     => $this->clientId,
            'code'          => $code,
            'redirect_uri'  => $redirectUri,
            'code_verifier' => $codeVerifier,
        ];

        $response = $this->httpClient->post('/token', [
            'headers' => $headers,
            'form_params' => $params,
        ]);

        $rawContents = $response->getBody()->getContents();

        return Token::create(json_decode($rawContents, true));
    }

    /**
     * return logout URL
     *
     * @param string $redirectUri
     * @return string
     */
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
