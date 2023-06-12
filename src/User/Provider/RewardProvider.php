<?php

declare(strict_types=1);

namespace App\User\Provider;

use App\Authorization\Token\AuthorizationCodeToken as AuthorizationToken;
use App\Session\Container as SessionContainer;
use App\User\User;

class RewardProvider implements ReadUserStateInterface
{
    protected SessionContainer $session;

    public function __construct(SessionContainer $session)
    {
        $this->session = $session;
    }

    public function login(AuthorizationToken $authorizationToken): void
    {
        $this->session['authorization_token'] = $authorizationToken;

        $claims = $authorizationToken->getDecodedAccessToken()->getClaims();

        $this->session['user'] = new User(
            $claims['username'],
            User::SERVICE_TYPE_REWRD
        );

        $this->session['authenticated'] = true;
    }

    public function logout(): void
    {
        $this->session->clear();
    }

    public function isAuthenticated(): bool
    {
        return isset($this->session['authenticated']) && $this->session['authenticated'] === true;
    }

    public function getUser(): ?User
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
}
