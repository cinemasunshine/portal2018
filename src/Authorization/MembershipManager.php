<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Authorization\Provider\MembershipProvider;
use App\Authorization\Token\AuthorizationCodeToken;

class MembershipManager
{
    protected MembershipProvider $provider;

    public function __construct(MembershipProvider $provider)
    {
        $this->provider = $provider;
    }

    public function getSignupUrl(): string
    {
        return $this->provider->getSignupUrl();
    }

    public function getAuthorizationUrl(): string
    {
        return $this->provider->getAuthorizationUrl();
    }

    public function getLogoutUrl(): string
    {
        return $this->provider->getLogoutUrl();
    }

    public function requestToken(string $code): AuthorizationCodeToken
    {
        return $this->provider->requestAuthorizationCodeToken($code);
    }
}
