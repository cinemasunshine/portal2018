<?php

/**
 * AuthorizationCodeTokenTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Authorization\Token\DecodedAccessToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * AuthorizationCodeToken test
 */
final class AuthorizationCodeTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|AuthorizationCodeToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationCodeToken::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AuthorizationCodeToken::class);
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
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testDecodeAccessToken(): void
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
     * @return MockInterface|LegacyMockInterface|DecodedAccessToken
     */
    protected function createDecodedAccessTokenMock()
    {
        return Mockery::mock('overload:' . DecodedAccessToken::class);
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
    public function testGetRefreshToken(): void
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
     * @test
     */
    public function testSetRefreshToken(): void
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

    /**
     * @test
     */
    public function testGetExpires(): void
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
     * @test
     */
    public function testSetExpires(): void
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
     * @test
     */
    public function testGetIdToken(): void
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
     * @test
     */
    public function testSetIdToken(): void
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
