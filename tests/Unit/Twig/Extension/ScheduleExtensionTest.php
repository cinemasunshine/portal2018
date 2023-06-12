<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\ScheduleExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\ScheduleExtension
 * @testdox スケジュールに関するTwig拡張機能
 */
final class ScheduleExtensionTest extends TestCase
{
    /**
     * @param array{'api_url'?: string} $prams
     */
    private function createScheduleExtension(array $prams = []): ScheduleExtension
    {
        $settings = array_merge(['api_url' => 'https://api.example.com'], $prams);

        return new ScheduleExtension($settings);
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = $this->createScheduleExtension();

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
            ['schedule_api'],
        ];
    }

    /**
     * @covers ::getApiUrl
     * @test
     */
    public function APIのURLを返す(): void
    {
        // Arrange
        $extension = $this->createScheduleExtension(['api_url' => 'https://api.example.com']);

        // Act
        $result = $extension->getApiUrl();

        // Assert
        $this->assertSame('https://api.example.com', $result);
    }
}
