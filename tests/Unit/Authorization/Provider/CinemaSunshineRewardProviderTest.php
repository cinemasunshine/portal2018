<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Provider;

use App\Authorization\Provider\CinemaSunshineRewardProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Authorization\Provider\CinemaSunshineRewardProvider
 * @testdox シネマサンシャインリワードの認可プロバイダー
 */
class CinemaSunshineRewardProviderTest extends TestCase
{
    /**
     * @param array<string, mixed> $params
     */
    private function createProvider(array $params = []): CinemaSunshineRewardProvider
    {
        return new CinemaSunshineRewardProvider(
            $params['host'] ?? 'example.com',
            $params['client_id'] ?? 'client',
            $params['client_secret'] ?? 'secret',
            $params['scopes'] ?? ['openid'],
            $params['login_url'] ?? 'https://default.com/login',
            $params['logout_url'] ?? 'https://default.com/logout'
        );
    }

    /**
     * @covers ::getAuthorizationUrl
     * @test
     */
    public function 認可URLを取得する(): void
    {
        // Arrange
        $provider = $this->createProvider([
            'host' => 'auth.example.com',
            'client_id' => 'example_client',
            'scopes' => [
                'openid',
                'email',
            ],
            'login_url' => 'https://thissite.com/login',
        ]);

        // Act
        $result = $provider->getAuthorizationUrl('example_state');

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/authorize', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('code', $query['response_type']);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('openid email', $query['scope']);
        $this->assertSame('https://thissite.com/login', $query['redirect_uri']);
        $this->assertSame('S256', $query['code_challenge_method']);
        $this->assertNotEmpty($query['code_challenge']);
        $this->assertSame('example_state', $query['state']);
    }

    /**
     * @covers ::getPkceCode
     * @test
     */
    public function 認可URLを取得するとPKCEコードを取得できる(): void
    {
        // Arrange
        $provider = $this->createProvider();
        $provider->getAuthorizationUrl('dummy');

        // Act
        $result = $provider->getPkceCode();

        // Assert
        $this->assertNotNull($result);
    }

    /**
     * @covers ::getLogoutUrl
     * @test
     */
    public function ログアウトURLを取得する(): void
    {
        // Arrange
        $provider = $this->createProvider([
            'host' => 'auth.example.com',
            'client_id' => 'example_client',
            'logout_url' => 'https://thissite.com/logout',
        ]);

        // Act
        $result = $provider->getLogoutUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/logout', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('https://thissite.com/logout', $query['logout_uri']);
    }
}
