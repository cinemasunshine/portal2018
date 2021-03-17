<?php

/**
 * RefreshTokenTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Grant;

use App\Authorization\Grant\RefreshToken;
use App\Authorization\Token\AuthorizationCodeToken;
use GuzzleHttp\Client as HttpClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * RefreshToken test
 */
final class RefreshTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|RefreshToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(RefreshToken::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(RefreshToken::class);
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

        $refreshToken    = 'refresh_token';
        $createTokenData = $contents;

        $createTokenData['refresh_token'] = $refreshToken;

        $tokenMock = $this->createTokenMock();
        $tokenMock
            ->shouldReceive('create')
            ->once()
            ->with($createTokenData)
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

        $headers = ['foo' => 'bar'];
        $targetMock
            ->shouldReceive('getRequestHeaders')
            ->once()
            ->with($clientId, $clientSecret)
            ->andReturn($headers);

        $result = $targetMock->requestToken($refreshToken);
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
}
