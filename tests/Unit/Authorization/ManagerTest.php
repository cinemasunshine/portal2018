<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use App\Authorization\Manager as AuthorizationManager;
use App\Authorization\SessionContainer as AuthorizationSessionContainer;
use App\Authorization\Token\AuthorizationCodeToken;
use App\Session\SessionManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \App\Authorization\Manager
 * @testdox 認可処理を扱うクラス
 */
final class ManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private function createSessionManager(): SessionManager
    {
        $sessionConfig  = new StandardConfig();
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setStorage(new ArrayStorage());

        return $sessionManager;
    }

    /**
     * @return array<string, mixed>
     */
    private function createSettings(): array
    {
        return [
            'authorization_code_host' => 'dummy-auth.example.com',
            'authorization_code_client_id' => 'xxxxx',
            'authorization_code_client_secret' => 'xxxxxxxxxxx',
            'authorization_code_scope' => [
                'openid',
                'email',
            ],
        ];
    }

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationManager
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationManager::class);
    }

    /**
     * @param array<string, mixed> $settings
     * @return MockInterface|LegacyMockInterface|AuthorizationManager
     */
    protected function createTargetMockWithArgs(array $settings, AuthorizationSessionContainer $session)
    {
        return Mockery::mock(AuthorizationManager::class, [$settings, $session]);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AuthorizationManager::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationCodeGrant
     */
    protected function createAuthorizationCodeGrantMock()
    {
        return Mockery::mock(AuthorizationCodeGrant::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationCodeToken
     */
    protected function createAuthorizationCodeTokenMock()
    {
        return Mockery::mock(AuthorizationCodeToken::class);
    }

    /**
     * @test
     */
    public function testGetAuthorizationCodeGrunt(): void
    {
        $settings = [
            'authorization_code_host' => 'host',
            'authorization_code_client_id' => 'client_id',
            'authorization_code_client_secret' => 'client_secret',
            'authorization_code_scope' => ['scope'],
        ];

        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );

        $targetMock = $this->createTargetMockWithArgs($settings, $sessionContainer);
        $targetRef  = $this->createTargetReflection();

        $authorizationCodeGruntPropertyRef = $targetRef->getProperty('authorizationCodeGrunt');
        $authorizationCodeGruntPropertyRef->setAccessible(true);

        $this->assertNull($authorizationCodeGruntPropertyRef->getValue($targetMock));

        $targetMethodRef = $targetRef->getMethod('getAuthorizationCodeGrunt');
        $targetMethodRef->setAccessible(true);

        $result = $targetMethodRef->invoke($targetMock);

        $this->assertInstanceOf(
            AuthorizationCodeGrant::class,
            $authorizationCodeGruntPropertyRef->getValue($targetMock)
        );
        $this->assertEquals($authorizationCodeGruntPropertyRef->getValue($targetMock), $result);
    }

    /**
     * @test
     */
    public function testGetAuthorizationUrl(): void
    {
        $redirectUri      = 'https://example.com/redirect';
        $authorizationUrl = 'https://example.com/authorization';

        $authorizationCodeGruntMock = $this->createAuthorizationCodeGrantMock();
        $authorizationCodeGruntMock
            ->shouldReceive('getAuthorizationUrl')
            ->once()
            ->with(
                Mockery::type('string'),
                $redirectUri,
                Mockery::type('array'),
                Mockery::type('string')
            )
            ->andReturn($authorizationUrl);

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $targetMock
            ->shouldReceive('getAuthorizationCodeGrunt')
            ->once()
            ->with()
            ->andReturn($authorizationCodeGruntMock);
        $targetMock
            ->shouldReceive('getCodeVerifier')
            ->once()
            ->with()
            ->andReturn('code_verifier');
        $targetMock
            ->shouldReceive('getAuthorizationState')
            ->once()
            ->with()
            ->andReturn('authorization_state');

        $targetRef = $this->createTargetReflection();

        $scopeListPropertyRef = $targetRef->getProperty('scopeList');
        $scopeListPropertyRef->setAccessible(true);
        $scopeListPropertyRef->setValue($targetMock, []);

        $result = $targetMock->getAuthorizationUrl($redirectUri);
        $this->assertEquals($authorizationUrl, $result);
    }

    /**
     * @test
     */
    public function testCreateUniqueStr(): void
    {
        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('createUniqueStr');
        $targetMethodRef->setAccessible(true);

        $list = [];

        for ($i = 0; $i < 1000; $i++) {
            $list[] = $targetMethodRef->invoke($targetMock, 'test');
        }

        $uniqueList = array_unique($list, SORT_STRING);

        $this->assertEquals(count($list), count($uniqueList));
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function AuthorizationStateを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);

        // Act
        $result = $manager->getAuthorizationState();

        // Assert
        $this->assertSame($sessionContainer->getAuthorizationState(), $result);
    }

    /**
     * @covers ::getAuthorizationState
     * @test
     */
    public function 初回以降も同じAuthorizationStateを取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);

        // Act
        $first  = $manager->getAuthorizationState();
        $second = $manager->getAuthorizationState();

        // Assert
        $this->assertSame($first, $second);
    }

    /**
     * @covers ::clearAuthorizationState
     * @test
     */
    public function AuthorizationStateをクリアすると新たな値を取得する(): void
    {
        // Arrange
        $sessionManager   = $this->createSessionManager();
        $sessionContainer = new AuthorizationSessionContainer(
            $sessionManager->getContainer('test')
        );
        $settings         = $this->createSettings();
        $manager          = new AuthorizationManager($settings, $sessionContainer);
        $first            = $manager->getAuthorizationState();

        // Act
        $manager->clearAuthorizationState();

        // Assert
        $second = $manager->getAuthorizationState();
        $this->assertNotSame($first, $second);
    }

    /**
     * @test
     */
    public function testRequestToken(): void
    {
        $tokenMock = $this->createAuthorizationCodeTokenMock();

        $code         = 'example_code';
        $redirectUri  = 'http://example.com/redirect';
        $codeVerifier = 'unique_code_verifier';

        $authorizationCodeGruntMock = $this->createAuthorizationCodeGrantMock();
        $authorizationCodeGruntMock
            ->shouldReceive('requestToken')
            ->once()
            ->with(
                $code,
                $redirectUri,
                $codeVerifier
            )
            ->andReturn($tokenMock);

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $targetMock
            ->shouldReceive('getAuthorizationCodeGrunt')
            ->once()
            ->with()
            ->andReturn($authorizationCodeGruntMock);
        $targetMock
            ->shouldReceive('getCodeVerifier')
            ->once()
            ->with()
            ->andReturn($codeVerifier);

        $result = $targetMock->requestToken($code, $redirectUri);
        $this->assertEquals($tokenMock, $result);
    }

    /**
     * @test
     */
    public function testGetLogoutUrl(): void
    {
        $logoutUrl   = 'https://example.com/logout';
        $redirectUri = 'http://example.com/redirect';

        $authorizationCodeGruntMock = $this->createAuthorizationCodeGrantMock();
        $authorizationCodeGruntMock
            ->shouldReceive('getLogoutUrl')
            ->once()
            ->with($redirectUri)
            ->andReturn($logoutUrl);

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $targetMock
            ->shouldReceive('getAuthorizationCodeGrunt')
            ->once()
            ->with()
            ->andReturn($authorizationCodeGruntMock);

        $result = $targetMock->getLogoutUrl($redirectUri);
        $this->assertEquals($logoutUrl, $result);
    }
}
