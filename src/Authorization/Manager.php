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
        return $this->authorizationCodeGrunt->requestToken(
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
        return $this->authorizationCodeGrunt->getLogoutUrl($redirectUri);
    }
}
