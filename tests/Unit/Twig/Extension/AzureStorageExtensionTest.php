<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\File;
use App\Twig\Extension\AzureStorageExtension;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\AzureStorageExtension
 * @testdox Azure Storageに関するTwig拡張機能
 */
final class AzureStorageExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @param array{'protocol'?: string, 'account_name'?: string} $params
     */
    private function createBlobRestProxy(array $params = []): BlobRestProxy
    {
        $params['protocol']     ??= 'https';
        $params['account_name'] ??= 'devstoreaccount1';
        $connection               = sprintf(
            'DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;',
            $params['protocol'],
            $params['account_name'],
            'Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw=='
        );

        return BlobRestProxy::createBlobService($connection);
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = new AzureStorageExtension($this->createBlobRestProxy());

        // Act
        $functions = $extensions->getFunctions();

        // Assert
        $functionNames = [];

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
            $functionNames[] = $function->getName();
        }

        $this->assertContains($name, $functionNames);
    }

    /**
     * @return array<array{string}>
     */
    public function functionNameDataProvider(): array
    {
        return [
            ['blob_url'],
            ['file_url'],
        ];
    }

    /**
     * @covers ::blobUrl
     * @test
     */
    public function BlobのURLを取得＿PublicEndpointを設定した場合(): void
    {
        // Arrange
        $publicEndpoint = 'https://storage.example.com';
        $extensions     = new AzureStorageExtension(
            $this->createBlobRestProxy(),
            $publicEndpoint
        );

        // Act
        $result = $extensions->blobUrl('test', 'sample.txt');

        // Assert
        $this->assertSame('https://storage.example.com/test/sample.txt', $result);
    }

    /**
     * @covers ::blobUrl
     * @test
     */
    public function BlobのURLを取得＿PublicEndpointを設定しない場合(): void
    {
        // Arrange
        $blobRestProxy = $this->createBlobRestProxy([
            'protocol' => 'https',
            'account_name' => 'devstoreaccount1',
        ]);
        $extensions    = new AzureStorageExtension($blobRestProxy);

        // Act
        $result = $extensions->blobUrl('test', 'sample.txt');

        // Assert
        $this->assertSame(
            'https://devstoreaccount1.blob.core.windows.net/test/sample.txt',
            $result
        );
    }

    /**
     * @covers ::fileUrl
     * @test
     */
    public function FileのURLを取得(): void
    {
        // Arrange
        $blobRestProxy = $this->createBlobRestProxy([
            'protocol' => 'https',
            'account_name' => 'devstoreaccount1',
        ]);
        $extensions    = new AzureStorageExtension($blobRestProxy);

        /** @var MockInterface|LegacyMockInterface|File $fileMock */
        $fileMock = Mockery::mock(File::class);
        $fileMock
            ->shouldReceive('getName')
            ->andReturn('sample.txt');

        // Act
        $result = $extensions->fileUrl($fileMock);

        // Assert
        $this->assertSame(
            'https://devstoreaccount1.blob.core.windows.net/file/sample.txt',
            $result
        );
    }
}
