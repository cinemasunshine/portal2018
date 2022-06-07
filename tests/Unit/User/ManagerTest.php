<?php

/**
 * ManagerTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Authorization\Token\DecodedAccessToken;
use App\Session\SessionManager;
use App\User\Manager as UserManager;
use Laminas\Session\Config\StandardConfig;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class ManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SessionManager $sessionManager;

    protected function setUp(): void
    {
        $sessionConfig = new StandardConfig();
        $sessionConfig->setOptions(['name' => 'test']);
        $this->sessionManager = new SessionManager($sessionConfig);
    }

    protected function tearDown(): void
    {
        $this->sessionManager->getStorage()->clear();
    }

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationCodeToken
     */
    protected function createAuthorizationCodeTokenMock()
    {
        return Mockery::mock(AuthorizationCodeToken::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|DecodedAccessToken
     */
    protected function createDecodedAccessTokenMock()
    {
        return Mockery::mock(DecodedAccessToken::class);
    }

    /**
     * @test
     */
    public function testLogin(): void
    {
        $username = 'username';
        $claims   = ['username' => $username];

        $decodedAccessTokenMock = $this->createDecodedAccessTokenMock();
        $decodedAccessTokenMock
            ->shouldReceive('getClaims')
            ->once()
            ->with()
            ->andReturn($claims);

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();
        $authorizationCodeTokenMock
            ->shouldReceive('decodeAccessToken')
            ->once()
            ->with()
            ->andReturn($decodedAccessTokenMock);

        $sessionContainer = $this->sessionManager->getContainer();

        $userManager = new UserManager($sessionContainer);
        $userManager->login($authorizationCodeTokenMock);

        $this->assertSame(
            $authorizationCodeTokenMock,
            $sessionContainer['authorization_token']
        );
    }

    /**
     * @test
     */
    public function testLogout(): void
    {
        $sessionContainer         = $this->sessionManager->getContainer();
        $sessionContainer['hoge'] = 'hoge';

        $userManager = new UserManager($sessionContainer);
        $userManager->logout();

        $this->assertEmpty($sessionContainer['hoge']);
    }

    /**
     * @test
     */
    public function testIsAuthenticated(): void
    {
        $sessionContainer = $this->sessionManager->getContainer();
        $userManager      = new UserManager($sessionContainer);

        $this->assertFalse($userManager->isAuthenticated());

        $sessionContainer['authenticated'] = true;
        $this->assertTrue($userManager->isAuthenticated());
    }

    /**
     * @test
     */
    public function testGetUser(): void
    {
        $user = ['name' => 'username'];

        $sessionContainer         = $this->sessionManager->getContainer();
        $sessionContainer['user'] = $user;

        $userManager = new UserManager($sessionContainer);

        $this->assertSame($user, $userManager->getUser());
    }

    /**
     * @test
     */
    public function testGetAuthorizationTokenNotLogin(): void
    {
        $sessionContainer = $this->sessionManager->getContainer();
        $userManager      = new UserManager($sessionContainer);

        $this->assertNull($userManager->getAuthorizationToken());
    }

    /**
     * @test
     */
    public function testGetAuthorizationTokenLoggedIn(): void
    {
        $sessionContainer = $this->sessionManager->getContainer();
        $userManager      = new UserManager($sessionContainer);

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();

        $sessionContainer['authorization_token'] = $authorizationCodeTokenMock;
        $sessionContainer['authenticated']       = true;

        $this->assertSame($authorizationCodeTokenMock, $userManager->getAuthorizationToken());
    }

    /**
     * @test
     */
    public function testSetAuthorizationToken(): void
    {
        $sessionContainer = $this->sessionManager->getContainer();
        $userManager      = new UserManager($sessionContainer);

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();
        $userManager->setAuthorizationToken($authorizationCodeTokenMock);

        $this->assertEquals(
            $authorizationCodeTokenMock,
            $sessionContainer['authorization_token']
        );
    }
}
