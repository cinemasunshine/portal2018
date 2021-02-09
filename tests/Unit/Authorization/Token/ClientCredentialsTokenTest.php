<?php

/**
 * ClientCredentialsTokenTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\ClientCredentialsToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * ClientCredentialsToken test
 */
final class ClientCredentialsTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|ClientCredentialsToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ClientCredentialsToken::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(ClientCredentialsToken::class);
    }

    /**
     * test create
     *
     * モックがうまく出来ないのでプロパティにセットされているかでテストする。
     * setterの変更に注意。
     *
     * @test
     */
    public function testCreate(): void
    {
        $accessToken = 'example_access_token';
        $tokenType   = 'example_type';
        $expiresIn   = 3600;

        $data = [
            'access_token' => $accessToken,
            'token_type' => $tokenType,
            'expires_in' => $expiresIn,
        ];

        $result = ClientCredentialsToken::create($data);
        $this->assertInstanceOf(ClientCredentialsToken::class, $result);

        $targetRef = $this->createTargetReflection();

        $accessTokenPropertyRef = $targetRef->getProperty('accessToken');
        $accessTokenPropertyRef->setAccessible(true);
        $this->assertEquals($accessToken, $accessTokenPropertyRef->getValue($result));

        $tokenTypePropertyRef = $targetRef->getProperty('tokenType');
        $tokenTypePropertyRef->setAccessible(true);
        $this->assertEquals($tokenType, $tokenTypePropertyRef->getValue($result));

        $expiresInPropertyRef = $targetRef->getProperty('expiresIn');
        $expiresInPropertyRef->setAccessible(true);
        $this->assertEquals($expiresIn, $expiresInPropertyRef->getValue($result));
    }

    /**
     * @test
     */
    public function testGetAccessToken(): void
    {
        $accessToken = 'example_access_token';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $accessTokenPropertyRef = $targetRef->getProperty('accessToken');
        $accessTokenPropertyRef->setAccessible(true);
        $accessTokenPropertyRef->setValue($targetMock, $accessToken);

        $this->assertEquals($accessToken, $targetMock->getAccessToken());
    }

    /**
     * @test
     */
    public function testSetAccessToken(): void
    {
        $accessToken = 'example_access_token';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setAccessToken');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $accessToken);

        $accessTokenPropertyRef = $targetRef->getProperty('accessToken');
        $accessTokenPropertyRef->setAccessible(true);

        $this->assertEquals($accessToken, $accessTokenPropertyRef->getValue($targetMock));
    }

    /**
     * @test
     */
    public function testGetTokenType(): void
    {
        $tokenType = 'example_token_type';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $tokenTypePropertyRef = $targetRef->getProperty('tokenType');
        $tokenTypePropertyRef->setAccessible(true);
        $tokenTypePropertyRef->setValue($targetMock, $tokenType);

        $this->assertEquals($tokenType, $targetMock->getTokenType());
    }

    /**
     * @test
     */
    public function testSetTokenType(): void
    {
        $tokenType = 'example_token_type';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setTokenType');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $tokenType);

        $tokenTypePropertyRef = $targetRef->getProperty('tokenType');
        $tokenTypePropertyRef->setAccessible(true);

        $this->assertEquals($tokenType, $tokenTypePropertyRef->getValue($targetMock));
    }

    /**
     * @test
     */
    public function testGetExpiresIn(): void
    {
        $expiresIn = 3600;

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $expiresInPropertyRef = $targetRef->getProperty('expiresIn');
        $expiresInPropertyRef->setAccessible(true);
        $expiresInPropertyRef->setValue($targetMock, $expiresIn);

        $this->assertEquals($expiresIn, $targetMock->getExpiresIn());
    }

    /**
     * @test
     */
    public function testSetExpiresIn(): void
    {
        $expiresIn = 3600;

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setExpiresIn');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $expiresIn);

        $expiresInPropertyRef = $targetRef->getProperty('expiresIn');
        $expiresInPropertyRef->setAccessible(true);

        $this->assertEquals($expiresIn, $expiresInPropertyRef->getValue($targetMock));
    }
}
