<?php

/**
 * DecodedAccessTokenTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use Cinemasunshine\Portal\Authorization\Token\DecodedAccessToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * DecodedAccessToken test
 */
final class DecodedAccessTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create target mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|DecodedAccessToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(DecodedAccessToken::class);
    }

    /**
     * Create target reflection
     *
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(DecodedAccessToken::class);
    }

    /**
     * Encode JWT
     *
     * @param array $header
     * @param array $claims
     * @param string $signature
     * @return string
     */
    protected function encodeJWT(array $header, array $claims, string $signature): string
    {
        $headerBase64 = base64_encode(json_encode($header));
        $claimsBase64 = base64_encode(json_encode($claims));
        $signatureBase64 = base64_encode($signature);

        return implode('.', [ $headerBase64, $claimsBase64, $signatureBase64]);
    }

    /**
     * test decodeJWT
     *
     * @test
     * @return void
     */
    public function testDecodeJWT()
    {
        $header = [
            'foo' => 'example_header',
        ];
        $claims = [
            'bar' => 'example_claims',
        ];
        $signature = 'example_signature';
        $jwt = $this->encodeJWT($header, $claims, $signature);

        $result = DecodedAccessToken::decodeJWT($jwt);
        $this->assertInstanceOf(DecodedAccessToken::class, $result);
    }

    /**
     * test decodeJWT (segments few)
     *
     * @test
     * @return void
     */
    public function testDecodeJWTSegmentsFew()
    {
        $this->expectException(\UnexpectedValueException::class);
        DecodedAccessToken::decodeJWT('aaa.bbb');
    }

    /**
     * test decodeJWT (segments many)
     *
     * @test
     * @return void
     */
    public function testDecodeJWTSegmentsMany()
    {
        $this->expectException(\UnexpectedValueException::class);
        DecodedAccessToken::decodeJWT('aaa.bbb.ccc.ddd');
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $header = [
            'foo' => 'example_header',
        ];
        $claims = [
            'bar' => 'example_claims',
        ];
        $signature = 'example_signature';

        $targetMock = $this->createTargetMock();
        $targetRef = $this->createTargetReflection();

        // execute constructor
        $constructorRef = $targetRef->getConstructor();
        $constructorRef->invoke($targetMock, $header, $claims, $signature);

        // test property "header"
        $headerPropertyRef = $targetRef->getProperty('header');
        $headerPropertyRef->setAccessible(true);
        $this->assertEquals($header, $headerPropertyRef->getValue($targetMock));

        // test property "claims"
        $claimsPropertyRef = $targetRef->getProperty('claims');
        $claimsPropertyRef->setAccessible(true);
        $this->assertEquals($claims, $claimsPropertyRef->getValue($targetMock));

        // test property "signature"
        $signaturePropertyRef = $targetRef->getProperty('signature');
        $signaturePropertyRef->setAccessible(true);
        $this->assertEquals($signature, $signaturePropertyRef->getValue($targetMock));
    }

    /**
     * test getHeader
     *
     * @test
     * @return void
     */
    public function testGetHeader()
    {
        $header = [
            'foo' => 'example_header',
        ];
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();

        $headerPropertyRef = $targetRef->getProperty('header');
        $headerPropertyRef->setAccessible(true);
        $headerPropertyRef->setValue($targetMock, $header);

        $this->assertEquals($header, $targetMock->getHeader());
    }

    /**
     * test getClaims
     *
     * @test
     * @return void
     */
    public function testGetClaims()
    {
        $claims = [
            'bar' => 'example_claims',
        ];
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();

        $claimsPropertyRef = $targetRef->getProperty('claims');
        $claimsPropertyRef->setAccessible(true);
        $claimsPropertyRef->setValue($targetMock, $claims);

        $this->assertEquals($claims, $targetMock->getClaims());
    }

    /**
     * get getSignature
     *
     * @test
     * @return void
     */
    public function testGetSignature()
    {
        $signature = 'example_signature';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();

        $signaturePropertyRef = $targetRef->getProperty('signature');
        $signaturePropertyRef->setAccessible(true);
        $signaturePropertyRef->setValue($targetMock, $signature);

        $this->assertEquals($signature, $targetMock->getSignature());
    }
}
