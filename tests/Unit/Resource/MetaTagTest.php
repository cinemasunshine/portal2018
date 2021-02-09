<?php

/**
 * MetaTagTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Resource;

use App\Resource\MetaTag;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * MetaTag test
 */
final class MetaTagTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&MetaTag
     */
    protected function createTargetMock()
    {
        return Mockery::mock(MetaTag::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(MetaTag::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $title       = 'example title';
        $description = 'example description';
        $keywords    = 'example keywords';

        $metaTag = new MetaTag($title, $description, $keywords);

        $targetRef = $this->createTargetReflection();

        $titleRef = $targetRef->getProperty('title');
        $titleRef->setAccessible(true);
        $this->assertEquals($title, $titleRef->getValue($metaTag));

        $descriptionRef = $targetRef->getProperty('description');
        $descriptionRef->setAccessible(true);
        $this->assertEquals($description, $descriptionRef->getValue($metaTag));

        $keywordsRef = $targetRef->getProperty('keywords');
        $keywordsRef->setAccessible(true);
        $this->assertEquals($keywords, $keywordsRef->getValue($metaTag));
    }

    /**
     * @test
     */
    public function testGetTitle(): void
    {
        $title = 'example title';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $titleRef = $targetRef->getProperty('title');
        $titleRef->setAccessible(true);
        $titleRef->setValue($targetMock, $title);

        $this->assertEquals($title, $targetMock->getTitle());
    }

    /**
     * @test
     */
    public function testGetDescription(): void
    {
        $description = 'example description';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $descriptionRef = $targetRef->getProperty('description');
        $descriptionRef->setAccessible(true);
        $descriptionRef->setValue($targetMock, $description);

        $this->assertEquals($description, $targetMock->getDescription());
    }

    /**
     * @test
     */
    public function testGetKeywords(): void
    {
        $keywords = 'example keywords';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        $keywordsRef = $targetRef->getProperty('keywords');
        $keywordsRef->setAccessible(true);
        $keywordsRef->setValue($targetMock, $keywords);

        $this->assertEquals($keywords, $targetMock->getKeywords());
    }
}
