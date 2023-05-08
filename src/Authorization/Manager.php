<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use App\Authorization\Token\AuthorizationCodeToken as Token;

/**
 * Authorization Manager class
 *
 * Authorization Codeによる認可フローを実装します。
 * 全ての認可処理を実装するものではありません。
 */
class Manager
{
    protected ?AuthorizationCodeGrant $authorizationCodeGrunt = null;

    protected string $clientId;

    protected string $clientSecret;

    protected string $codeChallengeMethod = 'S256';

    protected string $host;

    /** @var string[] */
    protected array $scopeList;

    protected SessionContainer $session;

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
        $this->session->setAuthorizationState($this->createUniqueStr('authorization_state'));
    }

    protected function createUniqueStr(string $name, string $salt = 'salt'): string
    {
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }

    public function getAuthorizationState(): string
    {
        if (! $this->session->hasAuthorizationState()) {
            $this->initAuthorizationState();
        }

        return $this->session->getAuthorizationState();
    }

    public function clearAuthorizationState(): void
    {
        $this->session->clearAuthorizationState();
    }

    protected function initCodeVerifier(): void
    {
        $this->session->setCodeVerifier($this->createUniqueStr('code_verifier'));
    }

    /**
     * return code_verifier
     *
     * 新たに認証を開始する時は code_verifier を初期化してください。
     */
    protected function getCodeVerifier(): string
    {
        if (! $this->session->hasCodeVerifier()) {
            $this->initCodeVerifier();
        }

        return $this->session->getCodeVerifier();
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
}
