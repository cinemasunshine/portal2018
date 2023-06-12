<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Authorization\SessionContainer;
use App\Session\SessionManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Authorization\SessionContainer
 * @testdox 認可処理のセッションを扱うコンテナ
 */
class SessionContainerTest extends TestCase
{
    private function createSessionManager(): SessionManager
    {
        $sessionConfig  = new StandardConfig();
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setStorage(new ArrayStorage());

        return $sessionManager;
    }

    /**
     * @covers ::hasAuthorizationState
     * @test
     */
    public function AuthorizationStateが存在する場合はtrueを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));
        $sessionContainer->setAuthorizationState('example_state');

        // Act
        $result = $sessionContainer->hasAuthorizationState();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers ::hasAuthorizationState
     * @test
     */
    public function AuthorizationStateが存在しない場合はfalseを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $result = $sessionContainer->hasAuthorizationState();

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers ::setAuthorizationState
     * @test
     */
    public function AuthorizationStateが保存される(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $sessionContainer->setAuthorizationState('example_state');

        // Assert
        $this->assertSame('example_state', $sessionContainer->getAuthorizationState());
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function 保存したAuthorizationStateを取得できる(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));
        $sessionContainer->setAuthorizationState('example_state');

        // Act
        $result = $sessionContainer->getAuthorizationState();

        // Assert
        $this->assertSame('example_state', $result);
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function AuthorizationStateが保存されていない状態ではnullを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $result = $sessionContainer->getAuthorizationState();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers ::hasCodeVerifier
     * @test
     */
    public function CodeVerifierが存在する場合はtrueを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));
        $sessionContainer->setCodeVerifier('example_coce_verifier');

        // Act
        $result = $sessionContainer->hasCodeVerifier();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers ::hasCodeVerifier
     * @test
     */
    public function CodeVerifierが存在しない場合はfalseを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $result = $sessionContainer->hasCodeVerifier();

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers ::setCodeVerifier
     * @test
     */
    public function CodeVerifierが保存される(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $sessionContainer->setCodeVerifier('example_coce_verifier');

        // Assert
        $this->assertSame('example_coce_verifier', $sessionContainer->getCodeVerifier());
    }

    /**
     * @covers ::getCodeVerifier
     * @test
     */
    public function 保存したCodeVerifierを取得できる(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));
        $sessionContainer->setCodeVerifier('example_coce_verifier');

        // Act
        $result = $sessionContainer->getCodeVerifier();

        // Assert
        $this->assertSame('example_coce_verifier', $result);
    }

    /**
     * @covers ::getCodeVerifier
     * @test
     */
    public function CodeVerifierが保存されていない状態ではnullを返す(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new SessionContainer($sessionManager->getContainer('test'));

        // Act
        $result = $sessionContainer->getCodeVerifier();

        // Assert
        $this->assertNull($result);
    }
}
