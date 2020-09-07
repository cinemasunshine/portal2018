<?php

/**
 * MetaTagTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Resource;

use App\Resource\MetaTag;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * MetaTag test
 */
final class MetaTagTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&MetaTag
     */
    protected function createTargetMock()
    {
        return Mockery::mock(MetaTag::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(MetaTag::class);
    }

    /**
     * @test
     * @return void
     */
    public function testConstruct()
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
     * @return void
     */
    public function testGetTitle()
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
     * @return void
     */
    public function testGetDescription()
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
     * @return void
     */
    public function testGetKeywords()
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
