<?php

declare(strict_types=1);

namespace Tests\Unit\User\Provider;

use App\User\Provider\MembershipProvider;
use App\User\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\User\Provider\MembershipProvider
 * @testdox シネマサンシャイン会員ユーザーについて扱うクラス
 */
class MembershipProviderTest extends TestCase
{
    /**
     * @covers ::isAuthenticated
     * @test
     */
    public function ログイン前は認証されていない状態(): void
    {
        // Arrange
        $membershipProvider = new MembershipProvider();

        // Act
        $result = $membershipProvider->isAuthenticated();

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
        $membershipProvider = new MembershipProvider();

        // Act
        $result = $membershipProvider->getUser();

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
        $membershipProvider = new MembershipProvider();

        // Act
        $membershipProvider->login();

        // Assert
        $this->assertTrue($membershipProvider->isAuthenticated());

        $user = $membershipProvider->getUser();
        $this->assertSame('', $user->getName());
        $this->assertSame(User::SERVICE_TYPE_MEMBERSHIP, $user->getServiceType());
    }
}
