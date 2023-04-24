<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\MembershipExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @covers \App\Twig\Extension\MembershipExtension
 * @testdox 会員に関するTwig拡張機能
 */
class MembershipExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @test
     */
    public function Twigヘルパー関数であるTwigFunctionのリストを返す(): void
    {
        // Arrange
        $extensions = new MembershipExtension([]);

        // Act
        $functions = $extensions->getFunctions();

        // Assrt
        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = new MembershipExtension([]);

        // Act
        $functions = $extensions->getFunctions();

        // Assert
        $functionNames = [];

        foreach ($functions as $function) {
            $functionNames[] = $function->getName();
        }

        $this->assertContains($name, $functionNames);
    }

    /**
     * @return array<array<string>>
     */
    public function functionNameDataProvider(): array
    {
        return [
            ['membership_mypage_url'],
        ];
    }

    /**
     * @covers ::getMyPageUrl
     * @test
     */
    public function マイページのURLを返す(): void
    {
        // Arrange
        $settings  = ['mypage_url' => 'https://example.com/mypage'];
        $extension = new MembershipExtension($settings);

        // Act
        $result = $extension->getMypageUrl();

        // Assert
        $this->assertSame('https://example.com/mypage', $result);
    }
}
