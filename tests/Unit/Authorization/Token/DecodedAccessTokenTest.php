<?php

/**
 * DecodedAccessTokenTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\DecodedAccessToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnexpectedValueException;

/**
 * DecodedAccessToken test
 */
final class DecodedAccessTokenTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|DecodedAccessToken
     */
    protected function createTargetMock()
    {
        return Mockery::mock(DecodedAccessToken::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(DecodedAccessToken::class);
    }

    /**
     * @param array<string, string> $header
     * @param array<string, mixed>  $claims
     */
    protected function encodeJWT(array $header, array $claims, string $signature): string
    {
        $headerBase64    = base64_encode(json_encode($header));
        $claimsBase64    = base64_encode(json_encode($claims));
        $signatureBase64 = base64_encode($signature);

        return implode('.', [$headerBase64, $claimsBase64, $signatureBase64]);
    }

    /**
     * @test
     */
    public function testDecodeJWT(): void
    {
        $header    = ['foo' => 'example_header'];
        $claims    = ['bar' => 'example_claims'];
        $signature = 'example_signature';

        $jwt = $this->encodeJWT($header, $claims, $signature);

        $result = DecodedAccessToken::decodeJWT($jwt);
        $this->assertInstanceOf(DecodedAccessToken::class, $result);
    }

    /**
     * test decodeJWT (segments few)
     *
     * @test
     */
    public function testDecodeJWTSegmentsFew(): void
    {
        $this->expectException(UnexpectedValueException::class);
        DecodedAccessToken::decodeJWT('aaa.bbb');
    }

    /**
     * test decodeJWT (segments many)
     *
     * @test
     */
    public function testDecodeJWTSegmentsMany(): void
    {
        $this->expectException(UnexpectedValueException::class);
        DecodedAccessToken::decodeJWT('aaa.bbb.ccc.ddd');
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $header    = ['foo' => 'example_header'];
        $claims    = ['bar' => 'example_claims'];
        $signature = 'example_signature';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

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
     * @test
     */
    public function testGetHeader(): void
    {
        $header = ['foo' => 'example_header'];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $headerPropertyRef = $targetRef->getProperty('header');
        $headerPropertyRef->setAccessible(true);
        $headerPropertyRef->setValue($targetMock, $header);

        $this->assertEquals($header, $targetMock->getHeader());
    }

    /**
     * @test
     */
    public function testGetClaims(): void
    {
        $claims = ['bar' => 'example_claims'];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $claimsPropertyRef = $targetRef->getProperty('claims');
        $claimsPropertyRef->setAccessible(true);
        $claimsPropertyRef->setValue($targetMock, $claims);

        $this->assertEquals($claims, $targetMock->getClaims());
    }

    /**
     * @test
     */
    public function testGetSignature(): void
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
