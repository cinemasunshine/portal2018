<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Session\SessionManager;
use App\User\Manager as UserManager;
use App\User\Provider\MembershipProvider;
use App\User\Provider\RewardProvider;
use App\User\User;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;
use Slim\Http\Cookies;

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

    /**
     * @covers ::getUserState
     * @test
     */
    public function Cookieにloginedが含まれる場合、ログイン状態のシネマサンシャイン会員ユーザの情報が取得できる(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        $membershipProvider = new MembershipProvider();

        $cookies = new Cookies(['logined' => 'foobar']);

        // Act
        $manager = new UserManager($rewardProvider, $membershipProvider, $cookies);
        $result  = $manager->getUserState();

        // Assert
        $this->assertTrue($result->isAuthenticated());

        $user = $result->getUser();
        $this->assertSame(User::SERVICE_TYPE_MEMBERSHIP, $user->getServiceType());
    }

    /**
     * @covers ::getUserState
     * @test
     */
    public function Cookieにlogined含まれない場合、非ログイン状態のユーザ情報が取得できる(): void
    {
        // Arrange
        $sessionManager = $this->createSessionManager();
        $rewardProvider = new RewardProvider($sessionManager->getContainer('test'));

        $membershipProvider = new MembershipProvider();

        $cookies = new Cookies();

        // Act
        $manager = new UserManager($rewardProvider, $membershipProvider, $cookies);
        $result  = $manager->getUserState();

        // Assert
        $this->assertNull($result->getUser());
    }
}
