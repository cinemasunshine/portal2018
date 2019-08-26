<?php
/**
 * AuthorizationCode.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Grant;

/**
 * Authorization Code Grant class
 */
class AuthorizationCode extends AbstractGrant
{
    /** @var string */
    protected $host;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

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

        $scope = $this->createScopeStr($scope);
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
     * create code_verifier
     *
     * @return string
     */
    public function createCodeVerifier(): string
    {
        return $this->createUniqueStr('code_verifier');
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
     * craete scope string
     *
     * @param array $scopeList
     * @return string
     */
    protected function createScopeStr(array $scopeList): string
    {
        return implode(' ', $scopeList);
    }
}
