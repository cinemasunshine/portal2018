<?php

/**
 * AuthorizationCodeTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Grant;

use App\Authorization\Grant\AuthorizationCode;
use App\Authorization\Token\AuthorizationCodeToken;
use GuzzleHttp\Client as HttpClient;
use LogicException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * AuthorizationCode test
 */
final class AuthorizationCodeTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationCode
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationCode::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AuthorizationCode::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|HttpClient
     */
    protected function createHttpClientMock()
    {
        return Mockery::mock(HttpClient::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $host         = 'example.com';
        $clientId     = 'client_id';
        $clientSecret = 'client_secret';

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $httpClientMock = $this->createHttpClientMock();
        $targetMock
            ->shouldReceive('createHttpClient')
            ->once()
            ->with('https://' . $host)
            ->andReturn($httpClientMock);

        $targetRef = $this->createTargetReflection();

        // execute constructor
        $constructorRef = $targetRef->getConstructor();
        $constructorRef->invoke($targetMock, $host, $clientId, $clientSecret);

        // test property "host"
        $hostPropertyRef = $targetRef->getProperty('host');
        $hostPropertyRef->setAccessible(true);
        $this->assertEquals($host, $hostPropertyRef->getValue($targetMock));

        // test property "clientId"
        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $this->assertEquals($clientId, $clientIdPropertyRef->getValue($targetMock));

        // test property "clientSecret"
        $clientSecretPropertyRef = $targetRef->getProperty('clientSecret');
        $clientSecretPropertyRef->setAccessible(true);
        $this->assertEquals($clientSecret, $clientSecretPropertyRef->getValue($targetMock));

        // test property "httpClient"
        $httpClientPropertyRef = $targetRef->getProperty('httpClient');
        $httpClientPropertyRef->setAccessible(true);
        $this->assertEquals($httpClientMock, $httpClientPropertyRef->getValue($targetMock));
    }

    /**
     * @test
     */
    public function testGetAuthorizationUrl(): void
    {
        $codeVerifier = 'code_verifier';
        $redirectUri  = 'https://example.com/redirect';
        $scope        = [
            'aaa',
            'bbb',
            'ccc',
        ];
        $state        = 'state_str';

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $targetRef = $this->createTargetReflection();

        $host = 'example.com';

        $hostPropertyRef = $targetRef->getProperty('host');
        $hostPropertyRef->setAccessible(true);
        $hostPropertyRef->setValue($targetMock, $host);

        $clientId = 'client_id';

        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $clientIdPropertyRef->setValue($targetMock, $clientId);

        $codeChallengeMethodPropertyRef = $targetRef->getProperty('codeChallengeMethod');
        $codeChallengeMethodPropertyRef->setAccessible(true);
        $codeChallengeMethod = $codeChallengeMethodPropertyRef->getValue($targetMock);

        $codeChallenge = 'code_challenge';
        $targetMock
            ->shouldReceive('generateCodeChallenge')
            ->once()
            ->with($codeVerifier, $codeChallengeMethod)
            ->andReturn($codeChallenge);

        $scopeStr = 'aaa bbb ccc';
        $targetMock
            ->shouldReceive('generateScopeStr')
            ->once()
            ->with($scope)
            ->andReturn($scopeStr);

        $result = $targetMock->getAuthorizationUrl($codeVerifier, $redirectUri, $scope, $state);
        $this->assertNotFalse(parse_url($result));

        $this->assertEquals('https', parse_url($result, PHP_URL_SCHEME));
        $this->assertEquals($host, parse_url($result, PHP_URL_HOST));
        $this->assertEquals('/authorize', parse_url($result, PHP_URL_PATH));

        $queryString = parse_url($result, PHP_URL_QUERY);
        parse_str($queryString, $params);

        $this->assertEquals('code', $params['response_type']);
        $this->assertEquals($clientId, $params['client_id']);
        $this->assertEquals($redirectUri, $params['redirect_uri']);
        $this->assertEquals($scopeStr, $params['scope']);
        $this->assertEquals($codeChallengeMethod, $params['code_challenge_method']);
        $this->assertEquals($codeChallenge, $params['code_challenge']);
        $this->assertEquals($state, $params['state']);
    }

    /**
     * @test
     */
    public function testGetAuthorizationUrlWithNotState(): void
    {
        $codeVerifier = 'code_verifier';
        $redirectUri  = 'https://example.com/redirect';
        $scope        = [
            'aaa',
            'bbb',
            'ccc',
        ];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $targetRef = $this->createTargetReflection();

        $hostPropertyRef = $targetRef->getProperty('host');
        $hostPropertyRef->setAccessible(true);
        $hostPropertyRef->setValue($targetMock, 'example.com');

        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $clientIdPropertyRef->setValue($targetMock, 'client_id');

        $targetMock
            ->shouldReceive('generateCodeChallenge')
            ->andReturn('code_challenge');

        $targetMock
            ->shouldReceive('generateScopeStr')
            ->andReturn('aaa bbb ccc');

        $result = $targetMock->getAuthorizationUrl($codeVerifier, $redirectUri, $scope);

        $queryString = parse_url($result, PHP_URL_QUERY);
        parse_str($queryString, $params);

        $this->assertArrayNotHasKey('state', $params);
    }

    /**
     * @test
     */
    public function testGenerateCodeChallenge(): void
    {
        $codeVerifier = 'code_verifier';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('generateCodeChallenge');
        $targetMethodRef->setAccessible(true);

        $result        = $targetMethodRef->invoke($targetMock, $codeVerifier, 'S256');
        $decodedResult = base64_decode($result);
        $this->assertEquals(
            hash('sha256', $codeVerifier),
            $decodedResult
        );

        $this->expectException(LogicException::class);
        $targetMethodRef->invoke($targetMock, $codeVerifier, 'invalid_method');
    }

    /**
     * @test
     */
    public function testGenerateScopeStr(): void
    {
        $scope = [
            'aaa',
            'bbb',
        ];

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('generateScopeStr');
        $targetMethodRef->setAccessible(true);

        $result = $targetMethodRef->invoke($targetMock, $scope);
        $this->assertEquals($scope[0] . ' ' . $scope[1], $result);
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testRequestToken(): void
    {
        $contents = ['foo' => 'bar'];

        $streamMock = $this->createStreamMock();
        $streamMock
            ->shouldReceive('getContents')
            ->once()
            ->with()
            ->andReturn(json_encode($contents));

        $responseMock = $this->createResponseMock();
        $responseMock
            ->shouldReceive('getBody')
            ->once()
            ->with()
            ->andReturn($streamMock);

        $httpClient = $this->createHttpClientMock();
        $httpClient
            ->shouldReceive('post')
            ->once()
            ->with('/token', Mockery::type('array'))
            ->andReturn($responseMock);

        $tokenMock = $this->createTokenMock();
        $tokenMock
            ->shouldReceive('create')
            ->once()
            ->with($contents)
            ->andReturn($tokenMock);

        $clientId     = 'client_id';
        $clientSecret = 'client_secret';

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $targetRef = $this->createTargetReflection();

        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $clientIdPropertyRef->setValue($targetMock, $clientId);

        $clientSecretPropertyRef = $targetRef->getProperty('clientSecret');
        $clientSecretPropertyRef->setAccessible(true);
        $clientSecretPropertyRef->setValue($targetMock, $clientSecret);

        $httpClientPropertyRef = $targetRef->getProperty('httpClient');
        $httpClientPropertyRef->setAccessible(true);
        $httpClientPropertyRef->setValue($targetMock, $httpClient);

        $headers = [];
        $targetMock
            ->shouldReceive('getRequestHeaders')
            ->once()
            ->with($clientId, $clientSecret)
            ->andReturn($headers);

        $code         = 'example_code';
        $redirectUri  = 'https://example.com/redirect';
        $codeVerifier = 'code_verifier';

        $result = $targetMock->requestToken($code, $redirectUri, $codeVerifier);
        $this->assertInstanceOf(AuthorizationCodeToken::class, $result);
    }

    /**
     * @return MockInterface|LegacyMockInterface
     */
    protected function createResponseMock()
    {
        return Mockery::mock('Response');
    }

    /**
     * @return MockInterface|LegacyMockInterface
     */
    protected function createStreamMock()
    {
        return Mockery::mock('Stream');
    }

    /**
     * @return MockInterface|LegacyMockInterface
     */
    protected function createTokenMock()
    {
        return Mockery::mock('overload:' . AuthorizationCodeToken::class);
    }

    /**
     * @test
     */
    public function testGetLogoutUrl(): void
    {
        $redirectUri = 'https://example.com/redirect';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();

        $host = 'example.com';

        $hostPropertyRef = $targetRef->getProperty('host');
        $hostPropertyRef->setAccessible(true);
        $hostPropertyRef->setValue($targetMock, $host);

        $clientId = 'client_id';

        $clientIdPropertyRef = $targetRef->getProperty('clientId');
        $clientIdPropertyRef->setAccessible(true);
        $clientIdPropertyRef->setValue($targetMock, $clientId);

        $result = $targetMock->getLogoutUrl($redirectUri);

        $this->assertNotFalse(parse_url($result));

        $this->assertEquals('https', parse_url($result, PHP_URL_SCHEME));
        $this->assertEquals($host, parse_url($result, PHP_URL_HOST));
        $this->assertEquals('/logout', parse_url($result, PHP_URL_PATH));

        $queryString = parse_url($result, PHP_URL_QUERY);
        parse_str($queryString, $params);

        $this->assertEquals($clientId, $params['client_id']);
        $this->assertEquals($redirectUri, $params['logout_uri']);
    }
}
