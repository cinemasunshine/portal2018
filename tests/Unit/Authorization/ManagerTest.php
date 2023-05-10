<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Authorization\Manager as AuthorizationManager;
use App\Authorization\SessionContainer as AuthorizationSessionContainer;
use App\Session\SessionManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Authorization\Manager
 * @testdox 認可処理を扱うクラス
 */
final class ManagerTest extends TestCase
{
    private function createSessionManager(): SessionManager
    {
        $sessionConfig  = new StandardConfig();
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setStorage(new ArrayStorage());

        return $sessionManager;
    }

    /**
     * @return array<string, mixed>
     */
    private function createSettings(): array
    {
        return [
            'authorization_code_host' => 'dummy-auth.example.com',
            'authorization_code_client_id' => 'xxxxx',
            'authorization_code_client_secret' => 'xxxxxxxxxxx',
            'authorization_code_scope' => [
                'openid',
                'email',
            ],
        ];
    }

    /**
     * @covers ::getAuthorizationUrl
     * @test
     */
    public function 認可URLを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );

        $settings = [
            'authorization_code_host' => 'auth.example.com',
            'authorization_code_client_id' => 'example_client',
            'authorization_code_client_secret' => 'example_secret',
            'authorization_code_scope' => [
                'openid',
                'email',
            ],
        ];

        $manager = new AuthorizationManager($settings, $sessionContainer);

        $redirectUri = 'https://example.com/redirect';

        // Act
        $result = $manager->getAuthorizationUrl($redirectUri);

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('openid email', $query['scope']);
        $this->assertSame('https://example.com/redirect', $query['redirect_uri']);
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function AuthorizationStateを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);

        // Act
        $result = $manager->getAuthorizationState();

        // Assert
        $this->assertSame($sessionContainer->getAuthorizationState(), $result);
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function 初回以降も同じAuthorizationStateを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);

        // Act
        $first  = $manager->getAuthorizationState();
        $second = $manager->getAuthorizationState();

        // Assert
        $this->assertSame($first, $second);
    }

    /**
     * @covers ::clearAuthorizationState
     * @test
     */
    public function AuthorizationStateがクリアされる(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);

        $manager->getAuthorizationUrl('https://dummy.com');

        // Act
        $manager->clearAuthorizationState();

        // Assert
        $this->assertNull($manager->getAuthorizationState());
    }

    /**
     * @covers ::getLogoutUrl
     * @test
     */
    public function ログアウトURLを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );

        $settings = [
            'authorization_code_host' => 'auth.example.com',
            'authorization_code_client_id' => 'example_client',
            'authorization_code_client_secret' => 'example_secret',
            'authorization_code_scope' => [
                'openid',
                'email',
            ],
        ];

        $manager = new AuthorizationManager($settings, $sessionContainer);

        $redirectUri = 'https://example.com/redirect';

        // Act
        $result = $manager->getLogoutUrl($redirectUri);

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('auth.example.com', $parsedUrl['host']);

        parse_str($parsedUrl['query'], $query);
        $this->assertSame('example_client', $query['client_id']);
        $this->assertSame('https://example.com/redirect', $query['logout_uri']);
    }
}
