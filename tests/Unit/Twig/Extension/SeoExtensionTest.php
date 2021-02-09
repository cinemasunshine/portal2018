<?php

/**
 * SeoExtension.php
 */

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
 * SEO extension test
 */
final class SeoExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var string|null */
    protected $file;

    protected function setUp(): void
    {
        $this->file = __DIR__ . '/data/seo.json';
    }

    /**
     * @return MockInterface&LegacyMockInterface&SeoExtension
     */
    protected function createTargetMock()
    {
        return Mockery::mock(SeoExtension::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(SeoExtension::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $file  = $this->file;
        $metas = [
            'test' => new MetaTag('example title', 'example description', 'hoge, huge'),
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('loadMetas')
            ->once()
            ->with($file)
            ->andReturn($metas);

        $targetRef = $this->createTargetReflection();

        $constructorRef = $targetRef->getConstructor();
        $constructorRef->invoke($targetMock, $file);
        $metasRef = $targetRef->getProperty('metas');
        $metasRef->setAccessible(true);

        $this->assertEquals($metas, $metasRef->getValue($targetMock));
    }

    /**
     * @test
     */
    public function testConstructInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $file = __DIR__ . '/not_exist.json';
        new SeoExtension($file);
    }

    /**
     * @test
     */
    public function testLoadMetas(): void
    {
        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $loadMetasRef = $targetRef->getMethod('loadMetas');
        $loadMetasRef->setAccessible(true);
        $result = $loadMetasRef->invoke($targetMock, $this->file);

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
     * @test
     */
    public function testGetFunctions(): void
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $functions = $targetMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @test
     */
    public function testGetTitle(): void
    {
        $key   = 'test';
        $title = 'example title';
        $metas = [
            $key => new MetaTag($title, 'example description', 'hoge, huge'),
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $metasRef = $targetRef->getProperty('metas');
        $metasRef->setAccessible(true);
        $metasRef->setValue($targetMock, $metas);

        $this->assertEquals($title, $targetMock->getTilte($key));
        $this->assertEquals('', $targetMock->getTilte('not_exist'));
    }

    /**
     * @test
     */
    public function testGetDescription(): void
    {
        $key         = 'test';
        $description = 'example description';
        $metas       = [
            $key => new MetaTag('example title', $description, 'hoge, huge'),
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $metasRef = $targetRef->getProperty('metas');
        $metasRef->setAccessible(true);
        $metasRef->setValue($targetMock, $metas);

        $this->assertEquals($description, $targetMock->getDescription($key));
        $this->assertEquals('', $targetMock->getDescription('not_exist'));
    }

    /**
     * @test
     */
    public function testGetKeywords(): void
    {
        $key      = 'test';
        $keywords = 'hoge, huge';
        $metas    = [
            $key => new MetaTag('example title', 'example description', $keywords),
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $metasRef = $targetRef->getProperty('metas');
        $metasRef->setAccessible(true);
        $metasRef->setValue($targetMock, $metas);

        $this->assertEquals($keywords, $targetMock->getKeywords($key));
        $this->assertEquals('', $targetMock->getKeywords('not_exist'));
    }
}
