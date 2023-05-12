<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Session\SessionManager;
use App\User\Manager as UserManager;
use App\User\User;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\User\Manager
 * @testdox ユーザーについて扱うクラス
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

    private function createAuthorizationCodeToken(string $accessToken): AuthorizationCodeToken
    {
        $accessToken = new TestAccessToken($accessToken);

        return AuthorizationCodeToken::create($accessToken);
    }

    /**
     * @covers ::isAuthenticated
     * @test
     */
    public function ログイン前は認証されていない状態(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $manager        = new UserManager($sessionManager->getContainer('test'));

        // Act
        $result = $manager->isAuthenticated();

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers ::getUser
     * @test
     */
    public function ログイン前はユーザ情報が無い(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $manager        = new UserManager($sessionManager->getContainer('test'));

        // Act
        $result = $manager->getUser();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers ::getAuthorizationToken
     * @test
     */
    public function ログイン前は認可トークンが無い(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $manager        = new UserManager($sessionManager->getContainer('test'));

        // Act
        $result = $manager->getAuthorizationToken();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers ::login
     * @test
     */
    public function ログインするとログイン状態になる(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $manager        = new UserManager($sessionManager->getContainer('test'));

        // phpcs:disable Generic.Files.LineLength.TooLong
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIn0.BHWfixVfgjk4JG_j5qxZg_FpeZthQlnq9zu99wJxDgw';
        // phpcs:enable

        $authorizationCodeToken = $this->createAuthorizationCodeToken($accessToken);

        // Act
        $manager->login($authorizationCodeToken, User::SERVICE_TYPE_MEMBERSHIP);

        // Assert
        $this->assertTrue($manager->isAuthenticated());

        $user = $manager->getUser();
        $this->assertSame('John Doe', $user->getName());
        $this->assertSame(User::SERVICE_TYPE_MEMBERSHIP, $user->getServiceType());

        $this->assertSame($authorizationCodeToken, $manager->getAuthorizationToken());
    }

    /**
     * @covers ::methodName
     * @test
     */
    public function ログイン状態からログアウトをするとログインしていない状態になる(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $manager        = new UserManager($sessionManager->getContainer('test'));

        // phpcs:disable Generic.Files.LineLength.TooLong
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIn0.BHWfixVfgjk4JG_j5qxZg_FpeZthQlnq9zu99wJxDgw';
        // phpcs:enable

        $authorizationCodeToken = $this->createAuthorizationCodeToken($accessToken);

        // Act
        $manager->login($authorizationCodeToken, User::SERVICE_TYPE_REWRD);
        $manager->logout();

        // Assert
        $this->assertFalse($manager->isAuthenticated());
        $this->assertNull($manager->getUser());
        $this->assertNull($manager->getAuthorizationToken());
    }
}
