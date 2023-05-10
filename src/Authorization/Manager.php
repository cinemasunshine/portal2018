<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Authorization\Provider\CinemaSunshineRewardProvider;
use App\Authorization\Token\AuthorizationCodeToken;

class Manager
{
    protected CinemaSunshineRewardProvider $provider;
    protected SessionContainer $session;

    public function __construct(CinemaSunshineRewardProvider $provider, SessionContainer $session)
    {
        $this->provider = $provider;
        $this->session  = $session;
    }

    public function getAuthorizationUrl(): string
    {
        $this->initAuthorizationState();

        $url = $this->provider->getAuthorizationUrl($this->getAuthorizationState());

        $this->session->setCodeVerifier($this->provider->getPkceCode());

        return $url;
    }

    /**
     * Initialize authorization state
     *
     * slim/flashのような一時的なストレージのほうが安全ではあるが
     * このアプリケーション設計では使用できない。
     * （APIやiframeなどのリクエストで消えてしまう）
     */
    protected function initAuthorizationState(): void
    {
        $this->session->setAuthorizationState(StateToken::genelate('authorization_state'));
    }

    public function getAuthorizationState(): ?string
    {
        return $this->session->getAuthorizationState();
    }

    protected function getCodeVerifier(): ?string
    {
        return $this->session->getCodeVerifier();
    }

    public function requestToken(string $code): AuthorizationCodeToken
    {
        return $this->provider->requestAuthorizationCodeToken(
            $code,
            $this->getCodeVerifier()
        );
    }

    public function getLogoutUrl(): string
    {
        return $this->provider->getLogoutUrl();
    }
}
