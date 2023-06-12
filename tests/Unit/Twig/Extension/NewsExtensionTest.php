<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\News;
use App\Twig\Extension\NewsExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\NewsExtension
 * @testdox ニュースに関するTwig拡張機能
 */
final class NewsExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = new NewsExtension();

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
            ['news_category_label'],
            ['news_category_label_class'],
        ];
    }

    /**
     * @covers ::getCategoryLabel
     * @dataProvider categoryLabelDataProvider
     * @test
     */
    public function カテゴリーに対応するラベル文字列を返す(?string $expected, int $category): void
    {
        // Arrange
        $extensions = new NewsExtension();

        // Act
        $result = $extensions->getCategoryLabel($category);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<array{string|null, int}>
     */
    public function categoryLabelDataProvider(): array
    {
        return [
            ['ニュース', News::CATEGORY_NEWS],
            ['インフォメーション', News::CATEGORY_INFO],
            ['IMAX', News::CATEGORY_IMAX],
            ['4DX', News::CATEGORY_4DX],
            ['ScreenX', News::CATEGORY_SCREENX],
            ['ライブビューイング・イベント', News::CATEGORY_EVENT],
            ['4DX SCREEN', News::CATEGORY_4DX_SCREEN],
            [null, 99],
        ];
    }

    /**
     * @covers ::getCategoryLabelClass
     * @dataProvider categoryClassDataProvider
     * @test
     */
    public function カテゴリに対応するclass属性の値を返す(?string $expected, int $category): void
    {
        // Arrange
        $extensions = new NewsExtension();

        // Act
        $result = $extensions->getCategoryLabelClass($category);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<array{string|null, int}>
     */
    public function categoryClassDataProvider(): array
    {
        return [
            ['list-type-news', News::CATEGORY_NEWS],
            ['list-type-information', News::CATEGORY_INFO],
            ['list-type-imax', News::CATEGORY_IMAX],
            ['list-type-4dx', News::CATEGORY_4DX],
            ['list-type-scx', News::CATEGORY_SCREENX],
            ['list-type-event', News::CATEGORY_EVENT],
            ['list-type-4dxwscx', News::CATEGORY_4DX_SCREEN],
            [null, 99],
        ];
    }
}
