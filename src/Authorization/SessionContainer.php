<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Session\Container;

class SessionContainer
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function hasAuthorizationState(): bool
    {
        return isset($this->container['authorization_state']);
    }

    public function getAuthorizationState(): ?string
    {
        return $this->container['authorization_state'];
    }

    public function setAuthorizationState(string $authorizationState): void
    {
        $this->container['authorization_state'] = $authorizationState;
    }

    public function hasCodeVerifier(): bool
    {
        return isset($this->container['code_verifier']);
    }

    public function getCodeVerifier(): ?string
    {
        return $this->container['code_verifier'];
    }

    public function setCodeVerifier(string $codeVerifier): void
    {
        $this->container['code_verifier'] = $codeVerifier;
    }
}
