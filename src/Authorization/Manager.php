<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use App\Authorization\Token\AuthorizationCodeToken as Token;
use App\Authorization\Token\AuthorizationCodeToken;
use League\OAuth2\Client\Provider\GenericProvider;

/**
 * Authorization Manager class
 *
 * Authorization Codeによる認可フローを実装します。
 * 全ての認可処理を実装するものではありません。
 */
class Manager
{
    protected AuthorizationCodeGrant $authorizationCodeGrunt;

    protected GenericProvider $provider;

    protected SessionContainer $session;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $host         = $settings['authorization_code_host'];
        $clientId     = $settings['authorization_code_client_id'];
        $clientSecret = $settings['authorization_code_client_secret'];
        $scopes       = $settings['authorization_code_scope'];

        $this->authorizationCodeGrunt = new AuthorizationCodeGrant(
            $host,
            $clientId,
            $clientSecret
        );

        $this->provider = new GenericProvider([
            'pkceMethod' => GenericProvider::PKCE_METHOD_S256,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'urlAuthorize' => 'https://' . $host . '/authorize',
            'urlAccessToken' => 'https://' . $host . '/token',
            'urlResourceOwnerDetails' => 'https://' . $host . '/xxxxx',
            'scopes' => $scopes,
            'scopeSeparator' => ' ',
        ]);

        $this->session = $session;
    }

    public function getAuthorizationUrl(string $redirectUri): string
    {
        $this->initAuthorizationState();
        $url = $this->provider->getAuthorizationUrl([
            'redirect_uri' => $redirectUri,
            'state' => $this->getAuthorizationState(),
        ]);
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

    public function clearAuthorizationState(): void
    {
        $this->session->clearAuthorizationState();
    }

    /**
     * return code_verifier
     *
     * 新たに認証を開始する時は code_verifier を初期化してください。
     */
    protected function getCodeVerifier(): ?string
    {
        return $this->session->getCodeVerifier();
    }

    public function requestToken(string $code, string $redirectUri): Token
    {
        $this->provider->setPkceCode($this->getCodeVerifier());
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]);

        return AuthorizationCodeToken::create($accessToken);
    }

    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->authorizationCodeGrunt->getLogoutUrl($redirectUri);
    }
}
