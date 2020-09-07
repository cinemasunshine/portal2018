<?php

/**
 * AbstractGrantTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Authorization\Grant;

use App\Authorization\Grant\AbstractGrant;
use GuzzleHttp\Client as HttpClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * AbstractGrant test
 */
final class AbstractGrantTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create target mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|AbstractGrant
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AbstractGrant::class);
    }

    /**
     * Create target reflection
     *
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(AbstractGrant::class);
    }

    /**
     * test createHttpClient
     *
     * @test
     * @return void
     */
    public function testCreateHttpClient()
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
     * test getRequestHeaders
     *
     * @test
     * @return void
     */
    public function testGetRequestHeaders()
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
