<?php
/**
 * AuthorizationCode.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Grant;

use GuzzleHttp\Client as HttpClient;

use Cinemasunshine\Portal\Authorization\Token\AuthorizationCodeToken as Token;

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
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $baseUri = 'https://' . $this->host;
        $this->httpClient = $this->createHttpClient($baseUri);
    }

    /**
     * create HTTP client
     *
     * @param string $baseUri
     * @return HttpClient
     */
    protected function createHttpClient(string $baseUri): HttpClient
    {
        $config = [
            'base_uri' => $baseUri,
            'timeout' => 5, // ひとまず5秒
            'connect_timeout' => 5, // ひとまず5秒
            'http_errors' => true,
        ];

        return new HttpClient($config);
    }

    /**
     * return authorization URL
     *
     * @param string $codeVerifier
     * @param string $redirectUri
     * @param array $scope
     * @param string|null $state
     * @return string
     * @link https://m-p.backlog.jp/view/SASAKI-485
     */
    public function getAuthorizationUrl(
        string $codeVerifier,
        string $redirectUri,
        array $scope,
        ?string $state = null
    ): string {
        $codeChallengeMethod = $this->codeChallengeMethod;
        $codeChallenge = $this->generateCodeChallenge($codeVerifier, $codeChallengeMethod);

        $scope = $this->generateScopeStr($scope);
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
     * @return string
     * @throws \LogicException
     */
    protected function generateCodeChallenge(string $codeVerifier, string $codeChallengeMethod): string
    {
        $codeChallenge = '';

        switch ($codeChallengeMethod) {
            case 'S256':
                $codeChallenge = base64_encode(hash('sha256', $codeVerifier));
                break;

            default:
                throw new \LogicException(sprintf('code_challenge_method does not support %s.', $codeChallengeMethod));
                break;
        }

        return $codeChallenge;
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
     * @param string $codeVerifie
     * @return Token
     */
    public function requestToken(string $code, string $redirectUri, string $codeVerifie): Token
    {
        $endpoint = '/token';
        $headers = $this->getRequestHeaders($this->clientId, $this->clientSecret);
        $params = [
            'grant_type'   => $this->name,
            'client_id'    => $this->clientId,
            'code'         => $code,
            'redirect_uri' => $redirectUri,
            'code_verifie' => $codeVerifie,
        ];

        $response = $this->httpClient->post($endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]);

        $rawContents = $response->getBody()->getContents();

        return new Token(json_decode($rawContents, true));
    }

    /**
     * return request headers
     *
     * @param string $clientId
     * @param string $clientSecret
     * @return array
     */
    protected function getRequestHeaders(string $clientId, string $clientSecret): array
    {
        $encodedCredentials = base64_encode(sprintf('%s:%s', $clientId, $clientSecret));

        $headers = [
            'Authorization' => 'Basic ' . $encodedCredentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $headers;
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
