<?php

/**
 * ClientCredentialsTokenTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\ClientCredentialsToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * ClientCredentialsToken test
 */
final class ClientCredentialsTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create target mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|ClientCredentialsToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ClientCredentialsToken::class);
    }

    /**
     * Create target reflection
     *
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(ClientCredentialsToken::class);
    }

    /**
     * test create
     *
     * モックがうまく出来ないのでプロパティにセットされているかでテストする。
     * setterの変更に注意。
     *
     * @test
     * @return void
     */
    public function testCreate()
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
     * test getAccessToken
     *
     * @test
     * @return void
     */
    public function testGetAccessToken()
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
     * test setAccessToken
     *
     * @test
     * @return void
     */
    public function testSetAccessToken()
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
     * test getTokenType
     *
     * @test
     * @return void
     */
    public function testGetTokenType()
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
     * test setTokenType
     *
     * @test
     * @return void
     */
    public function testSetTokenType()
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
     * test getExpiresIn
     *
     * @test
     * @return void
     */
    public function testGetExpiresIn()
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
     * test setExpiresIn
     *
     * @test
     * @return void
     */
    public function testSetExpiresIn()
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
