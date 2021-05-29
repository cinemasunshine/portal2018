<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Resource\MetaTag;
use App\Twig\Extension\SeoExtension;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\SeoExtensionTest
 */
final class SeoExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected const DATA_FILE = __DIR__ . '/data/seo.json';

    /** @var SeoExtension */
    protected $extension;

    /**
     * @return MockInterface&LegacyMockInterface&SeoExtension
     */
    protected function createSeoExtensionMock()
    {
        return Mockery::mock(SeoExtension::class);
    }

    protected function createSeoExtensionReflection(): ReflectionClass
    {
        return new ReflectionClass(SeoExtension::class);
    }

    /**
     * @before
     */
    public function setUp(): void
    {
        $this->extension = new SeoExtension(self::DATA_FILE);
    }

    /**
     * @covers ::__construct
     * @test
     * @testdox __constructは引数のファイルが存在する場合、loadMetas()の結果をプロパティにセットする
     */
    public function testConstructCaseFileExists(): void
    {
        $metas = [
            'test' => new MetaTag('example title', 'example description', 'hoge, huge'),
        ];

        $seoExtensionMock = $this->createSeoExtensionMock();
        $seoExtensionMock->shouldAllowMockingProtectedMethods();

        $seoExtensionMock
            ->shouldReceive('loadMetas')
            ->once()
            ->with(self::DATA_FILE)
            ->andReturn($metas);

        $seoExtensionRef = $this->createSeoExtensionReflection();

        $constructorRef = $seoExtensionRef->getConstructor();
        $constructorRef->invoke($seoExtensionMock, self::DATA_FILE);

        $metasPropertyRef = $seoExtensionRef->getProperty('metas');
        $metasPropertyRef->setAccessible(true);

        $this->assertEquals($metas, $metasPropertyRef->getValue($seoExtensionMock));
    }

    /**
     * @covers ::__construct
     * @test
     * @testdox __constructは引数のファイルが存在しない場合、例外をthrowする
     */
    public function testConstructCaseFileNotExists(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SeoExtension(__DIR__ . '/not_exist.json');
    }

    /**
     * @covers ::loadMetas
     * @test
     * @testdox loadMetasは引数のファイルを読み込んで、MetaTagオブジェクトのリストを返す
     */
    public function testLoadMetas(): void
    {
        $seoExtensionMock = $this->createSeoExtensionMock();

        $seoExtensionRef = $this->createSeoExtensionReflection();

        $loadMetasMethodRef = $seoExtensionRef->getMethod('loadMetas');
        $loadMetasMethodRef->setAccessible(true);

        $result = $loadMetasMethodRef->invoke($seoExtensionMock, self::DATA_FILE);

        foreach ($result as $row) {
            $this->assertInstanceOf(MetaTag::class, $row);
        }

        /** @var MetaTag $metaTag */
        $metaTag = $result['test'];

        $this->assertEquals('example title', $metaTag->getTitle());
        $this->assertEquals('example description', $metaTag->getDescription());
        $this->assertEquals('hoge, fuge', $metaTag->getKeywords());
    }

    /**
     * @covers ::getFunctions
     * @test
     * @testdox getFunctionsはTwigFunctionの配列を返す
     */
    public function testGetFunctionsCaseReturnType(): void
    {
        $functions = $this->extension->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @return array<string,array<string>>
     */
    public function getFunctionNameDataProvider(): array
    {
        return [
            'function meta_title' => ['meta_title'],
            'funciton meta_description' => ['meta_description'],
            'funciton meta_keywords' => ['meta_keywords'],
        ];
    }

    /**
     * @covers ::getFunctions
     * @dataProvider getFunctionNameDataProvider
     * @test
     * @testdox getFunctionsは指定のfunction名を含む
     */
    public function testGetFunctionsCaseFunctionNameExist(string $name): void
    {
        $functions = $this->extension->getFunctions();

        $functionNames = array_map(static function (TwigFunction $function) {
            return $function->getName();
        }, $functions);

        $this->assertTrue(in_array($name, $functionNames));
    }

    /**
     * @covers ::getTilte
     * @test
     * @testdox getTilteは引数keyのMetaTagが存在する場合、そのtitleを返す
     */
    public function testGetTitleCaseMetaTagExists(): void
    {
        $this->assertEquals(
            'example title',
            $this->extension->getTilte('test')
        );
    }

    /**
     * @covers ::getTilte
     * @test
     * @testdox getTilteは引数keyのMetaTagが存在しない場合、ブランク文字列を返す
     */
    public function testGetTitleCaseMetaTagNotExists(): void
    {
        $this->assertEquals(
            '',
            $this->extension->getTilte('not-exists')
        );
    }

    /**
     * @covers ::getDescription
     * @test
     * @testdox getDescriptionは引数keyのMetaTagが存在する場合、そのdescriptionを返す
     */
    public function testGetDescriptionCaseMetaTagExists(): void
    {
        $this->assertEquals(
            'example description',
            $this->extension->getDescription('test')
        );
    }

    /**
     * @covers ::getDescription
     * @test
     * @testdox getDescriptionは引数keyのMetaTagが存在しない場合、ブランク文字列を返す
     */
    public function testGetDescriptionCaseMetaTagNotExists(): void
    {
        $this->assertEquals(
            '',
            $this->extension->getDescription('not-exists')
        );
    }

    /**
     * @covers ::getKeywords
     * @test
     * @testdox getKeywordsは引数keyのMetaTagが存在する場合、そのkeywordsを返す
     */
    public function testGetKeywordsCaseMetaTagExists(): void
    {
        $this->assertEquals(
            'hoge, fuge',
            $this->extension->getKeywords('test')
        );
    }

    /**
     * @covers ::getKeywords
     * @test
     * @testdox getKeywordsは引数keyのMetaTagが存在しない場合、ブランク文字列を返す
     */
    public function testGetKeywordsCaseMetaTagNotExists(): void
    {
        $this->assertEquals(
            '',
            $this->extension->getKeywords('not-exists')
        );
    }
}
