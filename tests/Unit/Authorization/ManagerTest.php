<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;
use App\Authorization\Manager as AuthorizationManager;
use App\Authorization\Token\AuthorizationCodeToken;
use App\Session\Container as SessionContainer;
use App\Session\SessionManager;
use Laminas\Session\Config\StandardConfig;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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
    protected function createTargetMockWithArgs(array $settings, SessionContainer $session)
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
    public function testConstruct(): void
    {
        $settings = [
            'authorization_code_host' => 'host',
            'authorization_code_client_id' => 'client_id',
            'authorization_code_client_secret' => 'client_secret',
            'authorization_code_scope' => ['scope'],
        ];

        $sessionContainer = $this->sessionManager->getContainer();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        // execute constructor
        $constructorRef = $targetRef->getConstructor();
        $constructorRef->invoke($targetMock, $settings, $sessionContainer);

        // test property "host"
        $hostPropertyRef = $targetRef->getProperty('host');
        $hostPropertyRef->setAccessible(true);
        $this->assertEquals(
            $settings['authorization_code_host'],
            $hostPropertyRef->getValue($targetMock)
        );

        // test property "clientId"
        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $this->assertEquals(
            $settings['authorization_code_client_id'],
            $clientIdPropertyRef->getValue($targetMock)
        );

        // test property "clientSecret"
        $clientSecretPropertyRef = $targetRef->getProperty('clientSecret');
        $clientSecretPropertyRef->setAccessible(true);
        $this->assertEquals(
            $settings['authorization_code_client_secret'],
            $clientSecretPropertyRef->getValue($targetMock)
        );

        // test property "scopeList"
        $scopeListPropertyRef = $targetRef->getProperty('scopeList');
        $scopeListPropertyRef->setAccessible(true);
        $this->assertEquals(
            $settings['authorization_code_scope'],
            $scopeListPropertyRef->getValue($targetMock)
        );

        // test property "session"
        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $this->assertEquals(
            $sessionContainer,
            $sessionPropertyRef->getValue($targetMock)
        );
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

        $sessionContainer = $this->sessionManager->getContainer();

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
    public function testInitAuthorizationState(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $authorizationState = 'unique_authorization_state';
        $targetMock
            ->shouldReceive('createUniqueStr')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($authorizationState);

        $targetRef = $this->createTargetReflection();

        $sessionContainer = $this->sessionManager->getContainer();

        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($targetMock, $sessionContainer);

        $targetMethodRef = $targetRef->getMethod('initAuthorizationState');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock);

        $this->assertEquals($authorizationState, $sessionContainer['authorization_state']);
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
     * @test
     */
    public function testGetAuthorizationState(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $targetMock
            ->shouldReceive('initAuthorizationState')
            ->once()
            ->with()
            ->passthru();

        $targetRef = $this->createTargetReflection();

        $sessionContainer = $this->sessionManager->getContainer();

        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($targetMock, $sessionContainer);

        $result = $targetMock->getAuthorizationState();

        $this->assertEquals($sessionContainer['authorization_state'], $result);

        $targetMock
            ->shouldReceive('initAuthorizationState')
            ->never();

        $targetMock->getAuthorizationState();
    }

    /**
     * @test
     */
    public function testClearAuthorizationState(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $sessionContainer = $this->sessionManager->getContainer();

        $sessionContainer['authorization_state'] = 'example';

        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($targetMock, $sessionContainer);

        $targetMock->clearAuthorizationState();

        $this->assertNull($sessionContainer['authorization_state']);
    }

    /**
     * @test
     */
    public function testInitCodeVerifier(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $codeVerifier = 'unique_code_verifier';
        $targetMock
            ->shouldReceive('createUniqueStr')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($codeVerifier);

        $targetRef = $this->createTargetReflection();

        $sessionContainer = $this->sessionManager->getContainer();

        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($targetMock, $sessionContainer);

        $targetMethodRef = $targetRef->getMethod('initCodeVerifier');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock);

        $this->assertEquals($codeVerifier, $sessionContainer['code_verifier']);
    }

    /**
     * @test
     */
    public function testGetCodeVerifier(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $targetMock
            ->shouldReceive('initCodeVerifier')
            ->once()
            ->with()
            ->passthru();

        $targetRef = $this->createTargetReflection();

        $sessionContainer = $this->sessionManager->getContainer();

        $sessionPropertyRef = $targetRef->getProperty('session');
        $sessionPropertyRef->setAccessible(true);
        $sessionPropertyRef->setValue($targetMock, $sessionContainer);

        $targetMethodRef = $targetRef->getMethod('getCodeVerifier');
        $targetMethodRef->setAccessible(true);

        $result = $targetMethodRef->invoke($targetMock);

        $this->assertEquals($sessionContainer['code_verifier'], $result);

        $targetMock
            ->shouldReceive('initCodeVerifier')
            ->never();

        $targetMethodRef->invoke($targetMock);
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
