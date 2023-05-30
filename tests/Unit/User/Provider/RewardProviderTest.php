<?php

declare(strict_types=1);

namespace Tests\Unit\User\Provider;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Session\SessionManager;
use App\User\Provider\RewardProvider;
use App\User\User;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\User\Provider\RewardProvider
 * @testdox リワード会員ユーザーについて扱うクラス
 */
class RewardProviderTest extends TestCase
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
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        // Act
        $result = $rewardProvider->isAuthenticated();

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
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        // Act
        $result = $rewardProvider->getUser();

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
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        // Act
        $result = $rewardProvider->getAuthorizationToken();

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
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        // phpcs:disable Generic.Files.LineLength.TooLong
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIn0.BHWfixVfgjk4JG_j5qxZg_FpeZthQlnq9zu99wJxDgw';
        // phpcs:enable

        $authorizationCodeToken = $this->createAuthorizationCodeToken($accessToken);

        // Act
        $rewardProvider->login($authorizationCodeToken);

        // Assert
        $this->assertTrue($rewardProvider->isAuthenticated());

        $user = $rewardProvider->getUser();
        $this->assertSame('John Doe', $user->getName());
        $this->assertSame(User::SERVICE_TYPE_REWRD, $user->getServiceType());

        $this->assertSame($authorizationCodeToken, $rewardProvider->getAuthorizationToken());
    }

    /**
     * @covers ::logout
     * @test
     */
    public function ログイン状態からログアウトをするとログインしていない状態になる(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        // phpcs:disable Generic.Files.LineLength.TooLong
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIn0.BHWfixVfgjk4JG_j5qxZg_FpeZthQlnq9zu99wJxDgw';
        // phpcs:enable

        $authorizationCodeToken = $this->createAuthorizationCodeToken($accessToken);

        // Act
        $rewardProvider->login($authorizationCodeToken);
        $rewardProvider->logout();

        // Assert
        $this->assertFalse($rewardProvider->isAuthenticated());
        $this->assertNull($rewardProvider->getUser());
        $this->assertNull($rewardProvider->getAuthorizationToken());
    }
}
