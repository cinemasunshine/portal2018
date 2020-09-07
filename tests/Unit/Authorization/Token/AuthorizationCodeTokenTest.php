<?php

/**
 * AuthorizationCodeTokenTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Authorization\Token\DecodedAccessToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * AuthorizationCodeToken test
 */
final class AuthorizationCodeTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create target mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|AuthorizationCodeToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationCodeToken::class);
    }

    /**
     * Create target reflection
     *
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(AuthorizationCodeToken::class);
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
        $accessToken  = 'example_access_token';
        $tokenType    = 'example_type';
        $refreshToken = 'example_refresh_token';
        $expiresIn    = 3600;
        $idToken      = 'example_id_token';

        $data = [
            'access_token' => $accessToken,
            'token_type' => $tokenType,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn,
            'id_token' => $idToken,
        ];

        $result = AuthorizationCodeToken::create($data);
        $this->assertInstanceOf(AuthorizationCodeToken::class, $result);

        $targetRef = $this->createTargetReflection();

        $accessTokenPropertyRef = $targetRef->getProperty('accessToken');
        $accessTokenPropertyRef->setAccessible(true);
        $this->assertEquals($accessToken, $accessTokenPropertyRef->getValue($result));

        $tokenTypePropertyRef = $targetRef->getProperty('tokenType');
        $tokenTypePropertyRef->setAccessible(true);
        $this->assertEquals($tokenType, $tokenTypePropertyRef->getValue($result));

        $refreshTokenPropertyRef = $targetRef->getProperty('refreshToken');
        $refreshTokenPropertyRef->setAccessible(true);
        $this->assertEquals($refreshToken, $refreshTokenPropertyRef->getValue($result));

        $expiresInPropertyRef = $targetRef->getProperty('expiresIn');
        $expiresInPropertyRef->setAccessible(true);
        $this->assertEquals($expiresIn, $expiresInPropertyRef->getValue($result));

        $expiresPropertyRef = $targetRef->getProperty('expires');
        $expiresPropertyRef->setAccessible(true);
        $this->assertGreaterThan(time(), $expiresPropertyRef->getValue($result));

        $idTokenPropertyRef = $targetRef->getProperty('idToken');
        $idTokenPropertyRef->setAccessible(true);
        $this->assertEquals($idToken, $idTokenPropertyRef->getValue($result));
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
     * testdecodeAccessToken
     *
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testDecodeAccessToken()
    {
        $accessToken = 'example_access_token';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();

        $accessTokenPropertyRef = $targetRef->getProperty('accessToken');
        $accessTokenPropertyRef->setAccessible(true);
        $accessTokenPropertyRef->setValue($targetMock, $accessToken);

        $decodedAccessTokenMock = $this->createDecodedAccessTokenMock();
        $decodedAccessTokenMock
            ->shouldReceive('decodeJWT')
            ->once()
            ->with($accessToken)
            ->andReturn($decodedAccessTokenMock);

        $result = $targetMock->decodeAccessToken();
        $this->assertInstanceOf(DecodedAccessToken::class, $result);
    }

    /**
     * Create DecodedAccessToken mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|DecodedAccessToken
     */
    protected function createDecodedAccessTokenMock()
    {
        return Mockery::mock('overload:' . DecodedAccessToken::class);
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
     * test getRefreshToken
     *
     * @test
     * @return void
     */
    public function testGetRefreshToken()
    {
        $refreshToken = 'example_token_type';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $refreshTokenPropertyRef = $targetRef->getProperty('refreshToken');
        $refreshTokenPropertyRef->setAccessible(true);
        $refreshTokenPropertyRef->setValue($targetMock, $refreshToken);

        $this->assertEquals($refreshToken, $targetMock->getRefreshToken());
    }

    /**
     * test setRefreshToken
     *
     * @test
     * @return void
     */
    public function testSetRefreshToken()
    {
        $refreshToken = 'example_token_type';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setRefreshToken');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $refreshToken);

        $refreshTokenPropertyRef = $targetRef->getProperty('refreshToken');
        $refreshTokenPropertyRef->setAccessible(true);

        $this->assertEquals($refreshToken, $refreshTokenPropertyRef->getValue($targetMock));
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

    /**
     * test getExpires
     *
     * @test
     * @return void
     */
    public function testGetExpires()
    {
        $expires = time();

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $expiresPropertyRef = $targetRef->getProperty('expires');
        $expiresPropertyRef->setAccessible(true);
        $expiresPropertyRef->setValue($targetMock, $expires);

        $this->assertEquals($expires, $targetMock->getExpires());
    }

    /**
     * test setExpires
     *
     * @test
     * @return void
     */
    public function testSetExpires()
    {
        $expires = time();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setExpires');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $expires);

        $expiresPropertyRef = $targetRef->getProperty('expires');
        $expiresPropertyRef->setAccessible(true);

        $this->assertEquals($expires, $expiresPropertyRef->getValue($targetMock));
    }

    /**
     * test getIdToken
     *
     * @test
     * @return void
     */
    public function testGetIdToken()
    {
        $idToken = 'example_id_token';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $idTokenPropertyRef = $targetRef->getProperty('idToken');
        $idTokenPropertyRef->setAccessible(true);
        $idTokenPropertyRef->setValue($targetMock, $idToken);

        $this->assertEquals($idToken, $targetMock->getIdToken());
    }

    /**
     * test setIdToken
     *
     * @test
     * @return void
     */
    public function testSetIdToken()
    {
        $idToken = 'example_id_token';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('setIdToken');
        $targetMethodRef->setAccessible(true);

        $targetMethodRef->invoke($targetMock, $idToken);

        $idTokenPropertyRef = $targetRef->getProperty('idToken');
        $idTokenPropertyRef->setAccessible(true);

        $this->assertEquals($idToken, $idTokenPropertyRef->getValue($targetMock));
    }
}
