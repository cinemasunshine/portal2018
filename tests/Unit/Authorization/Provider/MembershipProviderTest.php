<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Provider;

use App\Authorization\Provider\MembershipProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Authorization\Provider\MembershipProvider
 * @testdox シネマサンシャイン会員の認可プロバイダー
 */
class MembershipProviderTest extends TestCase
{
    /**
     * @param array<string, mixed> $params
     */
    private function createProvider(array $params = []): MembershipProvider
    {
        return new MembershipProvider(
            $params['host'] ?? 'https://example.com',
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
            'host' => 'https://auth.example.com',
            'client_id' => 'example_client',
            'scopes' => ['openid+email'],
            'login_url' => 'https://thissite.com/login',
        ]);

        // Act
        $result = $provider->getAuthorizationUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/authorize', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('code', $query['response_type']);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('openid+email', $query['scope']);
        $this->assertSame('https://thissite.com/login', $query['redirect_uri']);
        $this->assertSame('login', $query['state']);
    }

    /**
     * @covers ::getLogoutUrl
     * @test
     */
    public function ログアウトURLを取得する(): void
    {
        // Arrange
        $provider = $this->createProvider([
            'host' => 'https://auth.example.com',
            'client_id' => 'example_client',
            'scopes' => ['openid+email'],
            'logout_url' => 'https://thissite.com/logout',
        ]);

        // Act
        $result = $provider->getLogoutUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/logout', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('code', $query['response_type']);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('openid+email', $query['scope']);
        $this->assertSame('https://thissite.com/logout', $query['logout_uri']);
        $this->assertSame('logout', $query['state']);
    }

    /**
     * @covers ::getSignupUrl
     * @test
     */
    public function 新規登録URLを取得する(): void
    {
        // Arrange
        $provider = $this->createProvider([
            'host' => 'https://auth.example.com',
            'client_id' => 'example_client',
            'scopes' => ['openid+email'],
            'login_url' => 'https://thissite.com/login',
        ]);

        // Act
        $result = $provider->getSignupUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/signup', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('code', $query['response_type']);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('openid+email', $query['scope']);
        $this->assertSame('https://thissite.com/login', $query['redirect_uri']);
        $this->assertSame('signup', $query['state']);
    }
}
