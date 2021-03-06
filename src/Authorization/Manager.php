<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use App\Authorization\Grant\RefreshToken as RefreshTokenGrant;
use App\Authorization\Token\AuthorizationCodeToken as Token;
use App\Session\Container as SessionContainer;

/**
 * Authorization Manager class
 *
 * Authorization Codeによる認可フローを実装します。
 * 全ての認可処理を実装するものではありません。
 */
class Manager
{
    /** @var AuthorizationCodeGrant|null */
    protected $authorizationCodeGrunt;

    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

    /** @var string */
    protected $host;

    /** @var RefreshTokenGrant|null */
    protected $refreshTokenGrant;

    /** @var string[] */
    protected $scopeList;

    /** @var SessionContainer */
    protected $session;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $this->host         = $settings['authorization_code_host'];
        $this->clientId     = $settings['authorization_code_client_id'];
        $this->clientSecret = $settings['authorization_code_client_secret'];
        $this->scopeList    = $settings['authorization_code_scope'];

        $this->session = $session;
    }

    protected function getAuthorizationCodeGrunt(): AuthorizationCodeGrant
    {
        if (! $this->authorizationCodeGrunt) {
            $this->authorizationCodeGrunt = new AuthorizationCodeGrant(
                $this->host,
                $this->clientId,
                $this->clientSecret
            );
        }

        return $this->authorizationCodeGrunt;
    }

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
     */
    protected function initAuthorizationState(): void
    {
        $this->session['authorization_state'] = $this->createUniqueStr('authorization_state');
    }

    protected function createUniqueStr(string $name, string $salt = 'salt'): string
    {
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }

    public function getAuthorizationState(): string
    {
        if (! isset($this->session['authorization_state'])) {
            $this->initAuthorizationState();
        }

        return $this->session['authorization_state'];
    }

    public function clearAuthorizationState(): void
    {
        unset($this->session['authorization_state']);
    }

    protected function initCodeVerifier(): void
    {
        $this->session['code_verifier'] = $this->createUniqueStr('code_verifier');
    }

    /**
     * return code_verifier
     *
     * 新たに認証を開始する時は code_verifier を初期化してください。
     */
    protected function getCodeVerifier(): string
    {
        if (! isset($this->session['code_verifier'])) {
            $this->initCodeVerifier();
        }

        return $this->session['code_verifier'];
    }

    public function requestToken(string $code, string $redirectUri): Token
    {
        return $this->getAuthorizationCodeGrunt()->requestToken(
            $code,
            $redirectUri,
            $this->getCodeVerifier()
        );
    }

    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->getAuthorizationCodeGrunt()->getLogoutUrl($redirectUri);
    }

    protected function getRefreshTokenGrant(): RefreshTokenGrant
    {
        if (! $this->refreshTokenGrant) {
            $this->refreshTokenGrant = new RefreshTokenGrant(
                $this->host,
                $this->clientId,
                $this->clientSecret
            );
        }

        return $this->refreshTokenGrant;
    }

    public function refreshToken(string $refreshToken): Token
    {
        return $this->getRefreshTokenGrant()->requestToken($refreshToken);
    }
}
