<?php

/**
 * UserExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Authorization\Manager as AuthorizationManager;
use App\Twig\Extension\UserExtension;
use App\User\Manager as UserManager;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFunction;

/**
 * User extension test
 */
final class UserExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationManager
     */
    protected function createAuthorizationManagerMock()
    {
        return Mockery::mock(AuthorizationManager::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|UserManager
     */
    protected function createUserManagerMock()
    {
        return Mockery::mock(UserManager::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $extensionMock            = Mockery::mock(UserExtension::class);
        $userManagerMock          = $this->createUserManagerMock();
        $authorizationManagerMock = $this->createAuthorizationManagerMock();

        $extensionClassRef = new ReflectionClass(UserExtension::class);

        // execute constructor
        $constructorRef = $extensionClassRef->getConstructor();
        $constructorRef->invoke($extensionMock, $userManagerMock, $authorizationManagerMock);

        // test property "userManager"
        $userManagerPropertyRef = $extensionClassRef->getProperty('userManager');
        $userManagerPropertyRef->setAccessible(true);
        $this->assertEquals(
            $userManagerMock,
            $userManagerPropertyRef->getValue($extensionMock)
        );

        // test property "authorizationManager"
        $authorizationManagerPropertyRef = $extensionClassRef->getProperty('authorizationManager');
        $authorizationManagerPropertyRef->setAccessible(true);
        $this->assertEquals(
            $authorizationManagerMock,
            $authorizationManagerPropertyRef->getValue($extensionMock)
        );
    }

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @test
     */
    public function testGetLoginUrl(): void
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $loginUrl = 'https://example.com/login';

        $authorizationManagerMock = $this->createAuthorizationManagerMock();
        $authorizationManagerMock
            ->shouldReceive('getAuthorizationUrl')
            ->once()
            ->with()
            ->andReturn($loginUrl);

        $extensionClassRef = new ReflectionClass(UserExtension::class);

        $authorizationManagerPropertyRef = $extensionClassRef->getProperty('authorizationManager');
        $authorizationManagerPropertyRef->setAccessible(true);
        $authorizationManagerPropertyRef->setValue($extensionMock, $authorizationManagerMock);

        $this->assertEquals($loginUrl, $extensionMock->getLoginUrl());
    }

    /**
     * @test
     */
    public function testGetLogoutUrl(): void
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $logoutUrl = 'https://example.com/logout';

        $authorizationManagerMock = $this->createAuthorizationManagerMock();
        $authorizationManagerMock
            ->shouldReceive('getLogoutUrl')
            ->once()
            ->with()
            ->andReturn($logoutUrl);

        $extensionClassRef = new ReflectionClass(UserExtension::class);

        $authorizationManagerPropertyRef = $extensionClassRef->getProperty('authorizationManager');
        $authorizationManagerPropertyRef->setAccessible(true);
        $authorizationManagerPropertyRef->setValue($extensionMock, $authorizationManagerMock);

        $this->assertEquals($logoutUrl, $extensionMock->getLogoutUrl());
    }

    /**
     * @test
     */
    public function testGetUser(): void
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $user = [];

        $userManagerMock = $this->createUserManagerMock();
        $userManagerMock
            ->shouldReceive('getUser')
            ->once()
            ->with()
            ->andReturn($user);

        $extensionClassRef = new ReflectionClass(UserExtension::class);

        $userManagerPropertyRef = $extensionClassRef->getProperty('userManager');
        $userManagerPropertyRef->setAccessible(true);
        $userManagerPropertyRef->setValue($extensionMock, $userManagerMock);

        $this->assertEquals($user, $extensionMock->getUser());
    }

    /**
     * @test
     */
    public function testIsLogin(): void
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $isAuthenticated = true;

        $userManagerMock = $this->createUserManagerMock();
        $userManagerMock
            ->shouldReceive('isAuthenticated')
            ->once()
            ->with()
            ->andReturn($isAuthenticated);

        $extensionClassRef = new ReflectionClass(UserExtension::class);

        $userManagerPropertyRef = $extensionClassRef->getProperty('userManager');
        $userManagerPropertyRef->setAccessible(true);
        $userManagerPropertyRef->setValue($extensionMock, $userManagerMock);

        $this->assertEquals($isAuthenticated, $extensionMock->isLogin());
    }
}
