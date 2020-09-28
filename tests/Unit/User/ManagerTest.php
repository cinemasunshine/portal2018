<?php

/**
 * ManagerTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
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
use PHPUnit\Framework\TestCase;

/**
 * Manager test
 */
final class ManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create SessionContainer mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|SessionContainer
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
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|ArrayObject
     */
    protected function createArrayObjectMock()
    {
        return Mockery::mock(ArrayObject::class);
    }

    /**
     * Create AuthorizationCodeToken mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|AuthorizationCodeToken
     */
    protected function createAuthorizationCodeTokenMock()
    {
        return Mockery::mock(AuthorizationCodeToken::class);
    }

    /**
     * Ceate DecodedAccessToken mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|DecodedAccessToken
     */
    protected function createDecodedAccessTokenMock()
    {
        return Mockery::mock(DecodedAccessToken::class);
    }

    /**
     * test getContainer
     *
     * @test
     * @return void
     */
    public function testGetContainer()
    {
        $sessionContainerMock = $this->createSessionContainerMock();
        $userManagerMock      = Mockery::mock(UserManager::class);

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

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
     * test logout
     *
     * @test
     * @return void
     */
    public function testLogin()
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

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $userManagerMock->login($authorizationCodeTokenMock);
    }

    /**
     * test logout
     *
     * @test
     * @return void
     */
    public function testLogout()
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock();
        $sessionContainerMock
            ->shouldReceive('clear')
            ->once()
            ->with();

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $userManagerMock->logout();
    }

    /**
     * test isAuthenticated
     *
     * @test
     * @return void
     */
    public function testIsAuthenticated()
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $this->assertFalse($userManagerMock->isAuthenticated());

        $sessionContainerMock['authenticated'] = true;
        $this->assertTrue($userManagerMock->isAuthenticated());
    }

    /**
     * test getUser
     *
     * @test
     * @return void
     */
    public function testGetUser()
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $user = ['name' => 'username'];

        $sessionContainerMock['user'] = $user;
        $this->assertEquals($user, $userManagerMock->getUser());
    }

    /**
     * test getAuthorizationToken
     *
     * @test
     * @return void
     */
    public function testGetAuthorizationToken()
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

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

        $sessionPropertyRef = $userManagerClassRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($userManagerMock, $sessionContainerMock);

        $this->assertEquals($authorizationCodeTokenMock, $userManagerMock->getAuthorizationToken());

        $this->assertNull($userManagerMock->getAuthorizationToken());
    }

    /**
     * test setAuthorizationToken
     *
     * @test
     * @return void
     */
    public function testSetAuthorizationToken()
    {
        $userManagerMock = Mockery::mock(UserManager::class)
            ->makePartial();

        $authorizationCodeTokenMock = $this->createAuthorizationCodeTokenMock();

        $sessionContainerMock = $this->createArrayObjectMock()
            ->makePartial();

        $userManagerClassRef = new \ReflectionClass(UserManager::class);

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
