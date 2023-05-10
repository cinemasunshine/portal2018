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
            $params['scopes'] ?? ['openid'],
            $params['login_url'] ?? 'https://default.com/login',
            $params['logouturl'] ?? 'https://default.com/logout'
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

        $provider = $this->createProvider();

        $manager = new AuthorizationManager($provider, $sessionContainer);

        // Act
        $result = $manager->getAuthorizationUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('/authorize', $parsedUrl['path']);
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

        $provider = $this->createProvider();

        $manager = new AuthorizationManager($provider, $sessionContainer);

        // Act
        $result = $manager->getLogoutUrl();

        // Assert
        $parsedUrl = parse_url($result);
        $this->assertSame('/logout', $parsedUrl['path']);
    }
}
