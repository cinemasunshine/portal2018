<?php
/**
 * Manager.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization;

use Zend\Session\Container as SessionContainer;

use Cinemasunshine\Portal\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use Cinemasunshine\Portal\Authorization\Token\AccessToken;

/**
 * Authorization Manager class
 */
class Manager
{
    /** @var SessionContainer */
    protected $session;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

    /** @var array */
    protected $authorizeScopeList;

    /** @var AuthorizationCodeGrant */
    protected $authorizationCodeGrunt;

    /**
     * construct
     *
     * @param array $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $this->authorizeScopeList = $settings['authorization_code_scope'];

        $this->authorizationCodeGrunt = new AuthorizationCodeGrant(
            $settings['authorization_code_host'],
            $settings['authorization_code_client_id'],
            $settings['authorization_code_client_secret']
        );

        $this->session = $session;
    }

    /**
     * return Authorization URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getAuthorizationUrl(string $redirectUri): string
    {
        return $this->authorizationCodeGrunt->getAuthorizationUrl(
            $this->getCodeVerifier(),
            $redirectUri,
            $this->authorizeScopeList,
            'todo'
        );
    }

    /**
     * Initialize code_verifier
     *
     * @return void
     */
    protected function initCodeVerifier(): void
    {
        $this->session['code_verifier'] = $this->authorizationCodeGrunt->createCodeVerifier();
    }

    /**
     * return code_verifier
     *
     * 新たに認証を開始する時は code_verifier を初期化してください。
     *
     * @return string
     */
    protected function getCodeVerifier(): string
    {
        if (!isset($this->session['code_verifier'])) {
            $this->initCodeVerifier();
        }

        return $this->session['code_verifier'];
    }

    /**
     * request access token
     *
     * @param string $code
     * @param string $redirectUri
     * @return AccessToken
     */
    public function requestAccessToken(string $code, string $redirectUri): AccessToken
    {
        return $this->authorizationCodeGrunt->requestAccessToken(
            $code,
            $redirectUri,
            $this->getCodeVerifier()
        );
    }

    /**
     * login
     *
     * logoutも適宜更新してください。
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function login(AccessToken $accessToken)
    {
        $this->session['access_token'] = $accessToken;
    }

    /**
     * ログイン判定
     *
     * @return boolean
     */
    public function isLogin(): bool
    {
        return isset($this->session['access_token']);
    }

    /**
     * return logout URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->authorizationCodeGrunt->getLogoutUrl($redirectUri);
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        unset($this->session['access_token']);
    }
}
