<?php

/**
 * AzureStorageExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\File;
use App\Twig\Extension\AzureStorageExtension;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * AzureStorage extension test
 */
final class AzureStorageExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create BlobRestProxy mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|BlobRestProxy
     */
    protected function crateBlobRestProxyMock()
    {
        return Mockery::mock(BlobRestProxy::class);
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $extensionMock = Mockery::mock(AzureStorageExtension::class);
        $blobRestProxyMock = $this->crateBlobRestProxyMock();
        $publicEndpoint = 'http://example.com';

        // execute constructor
        $extensionClassRef = new \ReflectionClass(AzureStorageExtension::class);
        $constructorRef = $extensionClassRef->getConstructor();
        $constructorRef->invoke($extensionMock, $blobRestProxyMock, $publicEndpoint);

        // test property "client"
        $clientPropertyRef = $extensionClassRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $this->assertEquals(
            $blobRestProxyMock,
            $clientPropertyRef->getValue($extensionMock)
        );

        // test property "publicEndpoint"
        $publicEndpointPropertyRef = $extensionClassRef->getProperty('publicEndpoint');
        $publicEndpointPropertyRef->setAccessible(true);
        $this->assertEquals(
            $publicEndpoint,
            $publicEndpointPropertyRef->getValue($extensionMock)
        );
    }

    /**
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
    {
        $extensionMock = Mockery::mock(AzureStorageExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * test blobUrl has publicEndpoint
     *
     * @test
     * @return void
     */
    public function testBlobUrlHasPublicEndpoint()
    {
        $extensionMock = Mockery::mock(AzureStorageExtension::class)
            ->makePartial();

        $extensionClassRef = new \ReflectionClass(AzureStorageExtension::class);

        $publicEndpointPropertyRef = $extensionClassRef->getProperty('publicEndpoint');
        $publicEndpointPropertyRef->setAccessible(true);

        $publicEndpoint = 'http://example.com';
        $publicEndpointPropertyRef->setValue($extensionMock, $publicEndpoint);

        $container = 'test';
        $blob = 'sample.txt';

        // execute
        $result = $extensionMock->blobUrl($container, $blob);
        $this->assertStringContainsString($publicEndpoint, $result);
        $this->assertStringContainsString($container, $result);
        $this->assertStringContainsString($blob, $result);
    }

    /**
     * test blobUrl do not has publicEndpoint
     *
     * @test
     * @return void
     */
    public function testBlobUrlDoNotHasPublicEndpoint()
    {
        $container = 'test';
        $blob = 'sample.txt';
        $url = 'http://storage.example.com/' . $container . '/' . $blob;

        $extensionMock = Mockery::mock(AzureStorageExtension::class)
            ->makePartial();

        $blobRestProxyMock = $this->crateBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobUrl')
            ->once()
            ->with($container, $blob)
            ->andReturn($url);

        $extensionClassRef = new \ReflectionClass(AzureStorageExtension::class);

        $clientPropertyRef = $extensionClassRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($extensionMock, $blobRestProxyMock);

        $publicEndpointPropertyRef = $extensionClassRef->getProperty('publicEndpoint');
        $publicEndpointPropertyRef->setAccessible(true);
        $publicEndpointPropertyRef->setValue($extensionMock, null);

        // execute
        $result = $extensionMock->blobUrl($container, $blob);
        $this->assertEquals($url, $result);
    }

    /**
     * test fileUrl
     *
     * @test
     * @return void
     */
    public function testFileUrl()
    {
        $container = File::getBlobContainer();
        $blob = 'sample.txt';
        $url = 'http://storage.example.com/' . $container . '/' . $blob;

        $fileMock = Mockery::mock(File::class);
        $fileMock
            ->shouldReceive('getName')
            ->once()
            ->with()
            ->andReturn($blob);

        $extensionMock = Mockery::mock(AzureStorageExtension::class)
            ->makePartial();
        $extensionMock
            ->shouldReceive('blobUrl')
            ->once()
            ->with($container, $blob)
            ->andReturn($url);

        $this->assertEquals($url, $extensionMock->fileUrl($fileMock));
    }
}
