<?php

/**
 * AbstractGrantTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Grant;

use App\Authorization\Grant\AbstractGrant;
use GuzzleHttp\Client as HttpClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * AbstractGrant test
 */
final class AbstractGrantTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|AbstractGrant
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AbstractGrant::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AbstractGrant::class);
    }

    /**
     * @test
     */
    public function testCreateHttpClient(): void
    {
        $baseUri = 'https://example.com';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('createHttpClient');
        $targetMethodRef->setAccessible(true);

        $result = $targetMethodRef->invoke($targetMock, $baseUri);

        $this->assertInstanceOf(HttpClient::class, $result);
        $this->assertEquals($baseUri, $result->getConfig('base_uri'));
    }

    /**
     * @test
     */
    public function testGetRequestHeaders(): void
    {
        $clientId     = 'client_id';
        $clientSecret = 'client_secret';

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $targetMethodRef = $targetRef->getMethod('getRequestHeaders');
        $targetMethodRef->setAccessible(true);

        $result = $targetMethodRef->invoke($targetMock, $clientId, $clientSecret);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('Authorization', $result);
        $this->assertArrayHasKey('Content-Type', $result);
    }
}
