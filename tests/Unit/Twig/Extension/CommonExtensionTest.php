<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\CommonExtension;
use DateTime;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\CommonExtension
 * @testdox Common Twig拡張機能
 */
final class CommonExtensionTest extends TestCase
{
    /**
     * @param array{'app_env'?: string} $params
     */
    private function createCommonExtension(array $params = []): CommonExtension
    {
        $params['app_env'] ??= 'test';

        return new CommonExtension($params['app_env']);
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = $this->createCommonExtension();

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
            ['app_env'],
            ['is_app_env'],
            ['facebook'],
            ['twitter'],
        ];
    }

    /**
     * @covers ::getAppEnv
     * @test
     */
    public function アプリケーション環境を示す文字列を取得する(): void
    {
        // Arrange
        $extensions = $this->createCommonExtension(['app_env' => 'test']);

        // Act
        $result = $extensions->getAppEnv();

        // Assert
        $this->assertSame('test', $result);
    }

    /**
     * @covers ::isAppEnv
     * @dataProvider isAppEnvDataProvider
     * @test
     *
     * @param string|string[] $env
     */
    public function 引数の文字列がアプリケーション環境にマッチするか判定する(bool $expected, $env): void
    {
        // Arrange
        $extensions = $this->createCommonExtension(['app_env' => 'test']);

        // Act
        $result = $extensions->isAppEnv($env);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<array{bool, string|string[]}>
     */
    public function isAppEnvDataProvider(): array
    {
        return [
            [true, 'test'],
            [true, ['test', 'dev']],
            [false, 'dev'],
            [false, ['dev', 'stg']],
        ];
    }

    /**
     * @covers ::getFacebookUrl
     * @test
     */
    public function 指定したfacebookアカウントのURLを返す(): void
    {
        // Arrange
        $extensions = $this->createCommonExtension();

        // Act
        $result = $extensions->getFacebookUrl('example');

        // Assert
        $this->assertSame('https://www.facebook.com/example', $result);
    }

    /**
     * @covers ::getTwitterUrl
     * @test
     */
    public function 指定したtwitterアカウントのURLを返す(): void
    {
        // Arrange
        $extensions = $this->createCommonExtension();

        // Act
        $result = $extensions->getTwitterUrl('example');

        // Assert
        $this->assertSame('https://twitter.com/example', $result);
    }

    /**
     * @covers ::getFilters
     * @dataProvider filterNameDataProvider
     * @test
     */
    public function 決まった名称のtwigフィルターが含まれる(string $name): void
    {
        // Arrange
        $extensions = $this->createCommonExtension();

        // Act
        $filters = $extensions->getFilters();

        // Assert
        $filterNames = [];

        foreach ($filters as $filter) {
            $this->assertInstanceOf(TwigFilter::class, $filter);
            $filterNames[] = $filter->getName();
        }

        $this->assertContains($name, $filterNames);
    }

    /**
     * @return array<array{string}>
     */
    public function filterNameDataProvider(): array
    {
        return [
            ['weekday'],
        ];
    }

    /**
     * @covers ::weekdayFilter
     * @dataProvider weekdayFilterProvider
     * @test
     */
    public function 日付から曜日を示す文字列を返す(string $expected, DateTime $date): void
    {
        // Arrange
        $extensions = $this->createCommonExtension();

        // Act
        $result = $extensions->weekdayFilter($date);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array{array{string,DateTime}}
     */
    public function weekdayFilterProvider(): array
    {
        return [
            ['月', new DateTime('2022-05-30')],
            ['火', new DateTime('2022-05-31')],
            ['水', new DateTime('2022-06-01')],
            ['木', new DateTime('2022-06-02')],
            ['金', new DateTime('2022-06-03')],
            ['土', new DateTime('2022-06-04')],
            ['日', new DateTime('2022-06-05')],
        ];
    }
}
