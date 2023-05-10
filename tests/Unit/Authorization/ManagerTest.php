<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Authorization\Manager as AuthorizationManager;
use App\Authorization\Provider\CinemaSunshineRewardProvider;
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
     * @param array<string, mixed> $params
     */
    private function createProvider(array $params = []): CinemaSunshineRewardProvider
    {
        return new CinemaSunshineRewardProvider(
            $params['host'] ?? 'example.com',
            $params['client_id'] ?? 'client',
            $params['client_secret'] ?? 'secret',
            $params['scopes'] ?? ['openid']
        );
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

        $provider = $this->createProvider([
            'host' => 'auth.example.com',
            'client_id' => 'example_client',
            'scopes' => [
                'openid',
                'email',
            ],
        ]);

        $manager = new AuthorizationManager($provider, $sessionContainer);

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
        $provider         = $this->createProvider();
        $manager          = new AuthorizationManager($provider, $sessionContainer);

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
        $provider         = $this->createProvider();
        $manager          = new AuthorizationManager($provider, $sessionContainer);

        // Act
        $first  = $manager->getAuthorizationState();
        $second = $manager->getAuthorizationState();

        // Assert
        $this->assertSame($first, $second);
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

        $provider = $this->createProvider([
            'host' => 'auth.example.com',
            'client_id' => 'example_client',
        ]);

        $manager = new AuthorizationManager($provider, $sessionContainer);

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
