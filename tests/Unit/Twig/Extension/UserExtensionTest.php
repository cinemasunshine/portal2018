<?php

/**
 * UserExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;
use Cinemasunshine\Portal\Twig\Extension\UserExtension;
use Cinemasunshine\Portal\User\Manager as UserManager;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * User extension test
 */
final class UserExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create AuthorizationManager mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|AuthorizationManager
     */
    protected function createAuthorizationManagerMock()
    {
        return Mockery::mock(AuthorizationManager::class);
    }

    /**
     * Create UserManager mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|UserManager
     */
    protected function createUserManagerMock()
    {
        return Mockery::mock(UserManager::class);
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $extensionMock = Mockery::mock(UserExtension::class);
        $userManagerMock = $this->createUserManagerMock();
        $authorizationManagerMock = $this->createAuthorizationManagerMock();

        // execute constructor
        $extensionClassRef = new \ReflectionClass(UserExtension::class);
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
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
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
     * test getLoginUrl
     *
     * @test
     * @return void
     */
    public function testGetLoginUrl()
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $redirectUrl = 'http://example.com/redirect';
        $loginUrl = 'https://example.com/login';

        $authorizationManagerMock = $this->createAuthorizationManagerMock();
        $authorizationManagerMock
            ->shouldReceive('getAuthorizationUrl')
            ->once()
            ->with($redirectUrl)
            ->andReturn($loginUrl);

        $extensionClassRef = new \ReflectionClass(UserExtension::class);
        $authorizationManagerPropertyRef = $extensionClassRef->getProperty('authorizationManager');
        $authorizationManagerPropertyRef->setAccessible(true);
        $authorizationManagerPropertyRef->setValue($extensionMock, $authorizationManagerMock);

        $this->assertEquals($loginUrl, $extensionMock->getLoginUrl($redirectUrl));
    }

    /**
     * test getLogoutUrl
     *
     * @test
     * @return void
     */
    public function testGetLogoutUrl()
    {
        $extensionMock = Mockery::mock(UserExtension::class)
            ->makePartial();

        $redirectUrl = 'http://example.com/redirect';
        $logoutUrl = 'https://example.com/logout';

        $authorizationManagerMock = $this->createAuthorizationManagerMock();
        $authorizationManagerMock
            ->shouldReceive('getLogoutUrl')
            ->once()
            ->with($redirectUrl)
            ->andReturn($logoutUrl);

        $extensionClassRef = new \ReflectionClass(UserExtension::class);
        $authorizationManagerPropertyRef = $extensionClassRef->getProperty('authorizationManager');
        $authorizationManagerPropertyRef->setAccessible(true);
        $authorizationManagerPropertyRef->setValue($extensionMock, $authorizationManagerMock);

        $this->assertEquals($logoutUrl, $extensionMock->getLogoutUrl($redirectUrl));
    }

    /**
     * test getUser
     *
     * @test
     * @return void
     */
    public function testGetUser()
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

        $extensionClassRef = new \ReflectionClass(UserExtension::class);
        $userManagerPropertyRef = $extensionClassRef->getProperty('userManager');
        $userManagerPropertyRef->setAccessible(true);
        $userManagerPropertyRef->setValue($extensionMock, $userManagerMock);

        $this->assertEquals($user, $extensionMock->getUser());
    }

    /**
     * test isLogin
     *
     * @test
     * @return void
     */
    public function testIsLogin()
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

        $extensionClassRef = new \ReflectionClass(UserExtension::class);
        $userManagerPropertyRef = $extensionClassRef->getProperty('userManager');
        $userManagerPropertyRef->setAccessible(true);
        $userManagerPropertyRef->setValue($extensionMock, $userManagerMock);

        $this->assertEquals($isAuthenticated, $extensionMock->isLogin());
    }
}
