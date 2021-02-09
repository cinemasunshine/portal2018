<?php

/**
 * ManagerTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Authorization\Token\DecodedAccessToken;
use App\Session\Container as SessionContainer;
use App\User\Manager as UserManager;
use Laminas\Stdlib\ArrayObject;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Manager test
 */
final class ManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|SessionContainer
     */
    protected function createSessionContainerMock()
    {
        return Mockery::mock(SessionContainer::class);
    }

    /**
     * Create ArrayObject mock
     *
     * 実際のセッション（$_SESSION）を利用しないように、
     * SessionContainerの代わりとする。
     * 現状ではoffsetGet()、offsetSet()が利用できれば良い。
     *
     * @return MockInterface|LegacyMockInterface|ArrayObject
     */
    protected function createArrayObjectMock()
    {
        return Mockery::mock(ArrayObject::class);
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
    public function testGetContainer(): void
    {
        $sessionContainerMock = $this->createSessionContainerMock();
        $userManagerMock      = Mockery::mock(UserManager::class);

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        // execute constructor
        $constructorRef = $userManagerClassRef->getConstructor();
        $constructorRef->invoke($userManagerMock, $sessionContainerMock);

        // test property "session"
        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $this->assertEquals(
            $sessionContainerMock,
            $sessionPropertyRef->getValue($userManagerMock)
        );
    }

    /**
     * @test
     */
    public function testLogin(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

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

        $sessionContainerMock = $this->createArrayObjectMock();
        $sessionContainerMock
            ->shouldReceive('offsetSet')
            ->once()
            ->with('authorization_token', $authorizationCodeTokenMock);

        $user = ['name' => $username];
        $sessionContainerMock
            ->shouldReceive('offsetSet')
            ->once()
            ->with('user', $user);

        $sessionContainerMock
            ->shouldReceive('offsetSet')
            ->once()
            ->with('authenticated', true);

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $userManagerMock->login($authorizationCodeTokenMock);
    }

    /**
     * @test
     */
    public function testLogout(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock();
        $sessionContainerMock
            ->shouldReceive('clear')
            ->once()
            ->with();

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $userManagerMock->logout();
    }

    /**
     * @test
     */
    public function testIsAuthenticated(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $this->assertFalse($userManagerMock->isAuthenticated());

        $sessionContainerMock['authenticated'] = true;
        $this->assertTrue($userManagerMock->isAuthenticated());
    }

    /**
     * @test
     */
    public function testGetUser(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $user = ['name' => 'username'];

        $sessionContainerMock['user'] = $user;
        $this->assertEquals($user, $userManagerMock->getUser());
    }

    /**
     * @test
     */
    public function testGetAuthorizationToken(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();
        $userManagerMock
            ->shouldReceive('isAuthenticated')
            ->andReturn(true, false);

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $sessionContainerMock['authorization_token'] = $authorizationCodeTokenMock;

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $this->assertEquals($authorizationCodeTokenMock, $userManagerMock->getAuthorizationToken());

        $this->assertNull($userManagerMock->getAuthorizationToken());
    }

    /**
     * @test
     */
    public function testSetAuthorizationToken(): void
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $userManagerMock->setAuthorizationToken($authorizationCodeTokenMock);

        $this->assertEquals(
            $authorizationCodeTokenMock,
            $sessionContainerMock['authorization_token']
        );
    }
}
