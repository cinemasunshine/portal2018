<?php
/**
 * Manager.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization;

use Cinemasunshine\Portal\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use Cinemasunshine\Portal\Authorization\Token\AuthorizationCodeToken as Token;
use Cinemasunshine\Portal\Session\Container as SessionContainer;

/**
 * Authorization Manager class
 *
 * Authorization Codeによる認可フローを実装します。
 * 全ての認可処理を実装するものではありません。
 */
class Manager
{
    /** @var AuthorizationCodeGrant */
    protected $authorizationCodeGrunt;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

    /** @var string */
    protected $host;

    /** @var array */
    protected $scopeList;

    /** @var SessionContainer */
    protected $session;

    /**
     * create unique string
     *
     * @param string $name
     * @param string $salt
     * @return string
     */
    protected static function createUniqueStr(string $name, string $salt = 'salt'): string
    {
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }

    /**
     * construct
     *
     * @param array $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $this->host = $settings['authorization_code_host'];
        $this->clientId = $settings['authorization_code_client_id'];
        $this->clientSecret = $settings['authorization_code_client_secret'];
        $this->scopeList = $settings['authorization_code_scope'];

        $this->session = $session;
    }

    /**
     * return Authorization Code Grunt
     *
     * @return AuthorizationCodeGrant
     */
    protected function getAuthorizationCodeGrunt(): AuthorizationCodeGrant
    {
        if (!$this->authorizationCodeGrunt) {
            $this->authorizationCodeGrunt = new AuthorizationCodeGrant(
                $this->host,
                $this->clientId,
                $this->clientSecret
            );
        }

        return $this->authorizationCodeGrunt;
    }

    /**
     * return Authorization URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getAuthorizationUrl(string $redirectUri): string
    {
        return $this->getAuthorizationCodeGrunt()->getAuthorizationUrl(
            $this->getCodeVerifier(),
            $redirectUri,
            $this->scopeList,
            $this->getAuthorizationState()
        );
    }

    /**
     * Initialize authorization state
     *
     * slim/flashのような一時的なストレージのほうが安全ではあるが
     * このアプリケーション設計では使用できない。
     * （APIやiframeなどのリクエストで消えてしまう）
     *
     * @return void
     */
    protected function initAuthorizationState()
    {
        $this->session['authorization_state'] = self::createUniqueStr('authorization_state');
    }

    /**
     * return authorization state
     *
     * @return string
     */
    public function getAuthorizationState(): string
    {
        if (!isset($this->session['authorization_state'])) {
            $this->initAuthorizationState();
        }

        return $this->session['authorization_state'];
    }

    /**
     * clear authorization state
     *
     * @return void
     */
    public function clearAuthorizationState()
    {
        unset($this->session['authorization_state']);
    }

    /**
     * Initialize code_verifier
     *
     * @return void
     */
    protected function initCodeVerifier(): void
    {
        $this->session['code_verifier'] = self::createUniqueStr('code_verifier');
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
     * request token
     *
     * @param string $code
     * @param string $redirectUri
     * @return Token
     */
    public function requestToken(string $code, string $redirectUri): Token
    {
        return $this->getAuthorizationCodeGrunt()->requestToken(
            $code,
            $redirectUri,
            $this->getCodeVerifier()
        );
    }

    /**
     * return logout URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->getAuthorizationCodeGrunt()->getLogoutUrl($redirectUri);
    }
}
