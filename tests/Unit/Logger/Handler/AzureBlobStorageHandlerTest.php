<?php

/**
 * AzureBlobStorageHandlerTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Logger\Handler;

use App\Logger\Handler\AzureBlobStorageHandler as Handler;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;

/**
 * AzureBlobStorage handler test
 */
final class AzureBlobStorageHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface|LegacyMockInterface|Handler
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Handler::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(Handler::class);
    }

    /**
     * @return MockInterface|LegacyMockInterface|BlobRestProxy
     */
    protected function createBlobRestProxyMock()
    {
        return Mockery::mock(BlobRestProxy::class);
    }

    /**
     * test createBlob (Blob Existing)
     *
     * @test
     */
    public function testCreateBlobExisting(): void
    {
        $container = 'example';
        $blob      = 'test.log';

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->never();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $blobRestProxyMock);

        $containerPropertyRef = $targetRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $containerPropertyRef->setValue($targetMock, $container);

        $blobPropertyRef = $targetRef->getProperty('blob');
        $blobPropertyRef->setAccessible(true);
        $blobPropertyRef->setValue($targetMock, $blob);

        $createBlobMethodRef = $targetRef->getMethod('createBlob');
        $createBlobMethodRef->setAccessible(true);

        // execute
        $createBlobMethodRef->invoke($targetMock);
    }

    /**
     * test createBlob (Blob Not Found)
     *
     * @test
     */
    public function testCreateBlobNotFound(): void
    {
        $container = 'example';
        $blob      = 'test.log';

        $exception = $this->createServiceException(404);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob)
            ->andThrow($exception);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->once();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $blobRestProxyMock);

        $containerPropertyRef = $targetRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $containerPropertyRef->setValue($targetMock, $container);

        $blobPropertyRef = $targetRef->getProperty('blob');
        $blobPropertyRef->setAccessible(true);
        $blobPropertyRef->setValue($targetMock, $blob);

        $createBlobMethodRef = $targetRef->getMethod('createBlob');
        $createBlobMethodRef->setAccessible(true);

        // execute
        $createBlobMethodRef->invoke($targetMock);
    }

    /**
     * test createBlob (Service Error)
     *
     * @test
     */
    public function testCreateBlobServiceError(): void
    {
        $container = 'example';
        $blob      = 'test.log';

        $exception = $this->createServiceException(500);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob)
            ->andThrow($exception);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->never();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $blobRestProxyMock);

        $containerPropertyRef = $targetRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $containerPropertyRef->setValue($targetMock, $container);

        $blobPropertyRef = $targetRef->getProperty('blob');
        $blobPropertyRef->setAccessible(true);
        $blobPropertyRef->setValue($targetMock, $blob);

        $this->expectException(ServiceException::class);

        $createBlobMethodRef = $targetRef->getMethod('createBlob');
        $createBlobMethodRef->setAccessible(true);

        // execute
        $createBlobMethodRef->invoke($targetMock);
    }

    protected function createServiceException(int $status): ServiceException
    {
        $responceMock = $this->createResponceMock();
        $responceMock
            ->shouldReceive('getStatusCode')
            ->andReturn($status);
        $responceMock
            ->shouldReceive('getReasonPhrase')
            ->andReturn('Reason Phrase');
        $responceMock
            ->shouldReceive('getBody')
            ->andReturn('Body');

        return new ServiceException($responceMock);
    }

    /**
     * @return MockInterface|LegacyMockInterface|ResponseInterface
     */
    protected function createResponceMock()
    {
        return Mockery::mock(ResponseInterface::class);
    }

    /**
     * @test
     */
    public function testWrite(): void
    {
        $isBlobCreated = false;
        $record        = ['formatted' => 'test'];

        $targetMock = $this->createTargetMock()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createBlob')
            ->once()
            ->with();
        $targetRef = $this->createTargetReflection();

        $isBlobCreatedPropertyRef = $targetRef->getProperty('isBlobCreated');
        $isBlobCreatedPropertyRef->setAccessible(true);
        $isBlobCreatedPropertyRef->setValue($targetMock, $isBlobCreated);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('appendBlock')
            ->once();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $blobRestProxyMock);

        $writeMethodRef = $targetRef->getMethod('write');
        $writeMethodRef->setAccessible(true);

        // execute
        $writeMethodRef->invoke($targetMock, $record);

        $this->assertTrue($isBlobCreatedPropertyRef->getValue($targetMock));
    }

    /**
     * test write (Is Blob Created)
     *
     * @test
     */
    public function testWriteIsBlobCreated(): void
    {
        $isBlobCreated = true;
        $record        = ['formatted' => 'test'];

        $targetMock = $this->createTargetMock()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createBlob')
            ->never();
        $targetRef = $this->createTargetReflection();

        $isBlobCreatedPropertyRef = $targetRef->getProperty('isBlobCreated');
        $isBlobCreatedPropertyRef->setAccessible(true);
        $isBlobCreatedPropertyRef->setValue($targetMock, $isBlobCreated);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('appendBlock')
            ->once();

        $clientPropertyRef = $targetRef->getProperty('client');
        $clientPropertyRef->setAccessible(true);
        $clientPropertyRef->setValue($targetMock, $blobRestProxyMock);

        $writeMethodRef = $targetRef->getMethod('write');
        $writeMethodRef->setAccessible(true);

        // execute
        $writeMethodRef->invoke($targetMock, $record);
    }
}
