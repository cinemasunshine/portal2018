<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Grant;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Authorization\Grant\AuthorizationCode;
 * @testdox Authorization Code Grantを扱うクラス
 */
final class AuthorizationCodeTest extends TestCase
{
    /**
     * @covers ::getLogoutUrl
     * @test
     */
    public function ログアウトURLを取得する(): void
    {
        // Arrange
        $grant       = new AuthorizationCodeGrant(
            'auth.example.com',
            'example_client',
            'example_secret',
        );
        $redirectUri = 'https://example.com/callback';

        // Act
        $result = $grant->getLogoutUrl($redirectUri);

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);
        $this->assertSame('/logout', $parsedUrl['path']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('https://example.com/callback', $query['logout_uri']);
    }
}
