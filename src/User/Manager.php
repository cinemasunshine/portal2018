<?php

declare(strict_types=1);

namespace App\User;

use App\Authorization\Token\AuthorizationCodeToken as AuthorizationToken;
use App\Session\Container as SessionContainer;

/**
 * User manager class
 */
class Manager
{
    /** @var SessionContainer */
    protected $session;

    public function __construct(SessionContainer $session)
    {
        $this->session = $session;
    }

    /**
     * login
     *
     * logoutも適宜更新してください。
     */
    public function login(AuthorizationToken $authorizationToken): void
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

    public function logout(): void
    {
        $this->session->clear();
    }

    /**
     * 認証判定
     */
    public function isAuthenticated(): bool
    {
        return isset($this->session['authenticated']) && $this->session['authenticated'] === true;
    }

    /**
     * return authenticated user data
     *
     * @return array<string, mixed>|null
     */
    public function getUser(): ?array
    {
        return $this->session['user'];
    }

    public function getAuthorizationToken(): ?AuthorizationToken
    {
        if ($this->isAuthenticated()) {
            return $this->session['authorization_token'];
        }

        return null;
    }

    public function setAuthorizationToken(AuthorizationToken $token): void
    {
        $this->session['authorization_token'] = $token;
    }
}
