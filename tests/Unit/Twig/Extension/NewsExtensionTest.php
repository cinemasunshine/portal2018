<?php

/**
 * NewsExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\News;
use App\Twig\Extension\NewsExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * News extension test
 */
final class NewsExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $extensionMock = Mockery::mock(NewsExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @test
     */
    public function testGetCategoryLabel(): void
    {
        $extensionMock = Mockery::mock(NewsExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->getCategoryLabel(News::CATEGORY_INFO));
        $this->assertNull($extensionMock->getCategoryLabel(99));
    }

    /**
     * @test
     */
    public function testGetCategoryLabelClass(): void
    {
        $extensionMock = Mockery::mock(NewsExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->getCategoryLabelClass(News::CATEGORY_INFO));
        $this->assertNull($extensionMock->getCategoryLabelClass(99));
    }
}
