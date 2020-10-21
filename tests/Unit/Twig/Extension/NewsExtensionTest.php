<?php

/**
 * NewsExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
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
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
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
     * test getCategoryLabel
     *
     * @test
     * @return void
     */
    public function testGetCategoryLabel()
    {
        $extensionMock = Mockery::mock(NewsExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->getCategoryLabel(News::CATEGORY_INFO));
        $this->assertNull($extensionMock->getCategoryLabel(99));
    }

    /**
     * test getCategoryLabelClass
     *
     * @test
     * @return void
     */
    public function testGetCategoryLabelClass()
    {
        $extensionMock = Mockery::mock(NewsExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->getCategoryLabelClass(News::CATEGORY_INFO));
        $this->assertNull($extensionMock->getCategoryLabelClass(99));
    }
}
