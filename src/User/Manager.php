<?php
/**
 * Manager.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\User;

use Cinemasunshine\Portal\Authorization\Token\AuthorizationCodeToken as AuthorizationToken;
use Cinemasunshine\Portal\Session\Container as SessionContainer;

/**
 * User manager class
 */
class Manager
{
    /** @var SessionContainer */
    protected $session;

    /**
     * construct
     *
     * @param SessionContainer $session
     */
    public function __construct(SessionContainer $session)
    {
        $this->session = $session;
    }

    /**
     * login
     *
     * logoutも適宜更新してください。
     *
     * @param AuthorizationToken $authorizationToken
     * @return void
     */
    public function login(AuthorizationToken $authorizationToken)
    {
        $this->session['authorization_token'] = $authorizationToken;

        /**
         * ユーザ情報
         * 情報が増えてきたらオブジェクト化など考える。
         */
        $claims = $authorizationToken->decodeAccessToken()->getClaims();
        $user = [
            'name' => $claims['username'],
        ];
        $this->session['user'] = $user;

        $this->session['authenticated'] = true;
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        $this->session->clear();
    }

    /**
     * 認証判定
     *
     * @return boolean
     */
    public function isAuthenticated(): bool
    {
        return isset($this->session['authenticated']) && $this->session['authenticated'] === true;
    }

    /**
     * return authenticated user data
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->session['user'];
    }

    /**
     * return authorization token
     *
     * @return AuthorizationToken|null
     */
    public function getAuthorizationToken(): ?AuthorizationToken
    {
        if ($this->isAuthenticated()) {
            return $this->session['authorization_token'];
        }

        return null;
    }
}
