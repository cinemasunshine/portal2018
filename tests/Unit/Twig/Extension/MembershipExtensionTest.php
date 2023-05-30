<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\MembershipExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\MembershipExtension
 * @testdox 会員に関するTwig拡張機能
 */
class MembershipExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extension = new MembershipExtension('https://membership.example.com');

        // Act
        $functions = $extension->getFunctions();

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
            ['membership_mypage_url'],
            ['membership_signup_url'],
            ['membership_login_url'],
            ['membership_logout_url'],
        ];
    }

    /**
     * @covers ::getMyPageUrl
     * @test
     */
    public function メソッドgetMyPageUrlは会員サイトトップのURLを返す(): void
    {
        // Arrange
        $extension = new MembershipExtension('https://membership.example.com');

        // Act
        $result = $extension->getMypageUrl();

        // Assert
        $this->assertSame('https://membership.example.com', $result);
    }

    /**
     * @covers ::getSignupUrl
     * @test
     */
    public function メソッドgetSignupUrlは会員サイトトップのURLを返す(): void
    {
        // Arrange
        $extension = new MembershipExtension('https://membership.example.com');

        // Act
        $result = $extension->getSignupUrl();

        // Assert
        $this->assertSame('https://membership.example.com', $result);
    }

    /**
     * @covers ::getLoginUrl
     * @test
     */
    public function メソッドgetLoginUrlは会員サイトトップのURLを返す(): void
    {
        // Arrange
        $extension = new MembershipExtension('https://membership.example.com');

        // Act
        $result = $extension->getSignupUrl();

        // Assert
        $this->assertSame('https://membership.example.com', $result);
    }

    /**
     * @covers ::getLogoutUrl
     * @test
     */
    public function メソッドgetLogoutUrlは会員サイトのログアウトURLを返す(): void
    {
        // Arrange
        $extension = new MembershipExtension('https://membership.example.com');

        // Act
        $result = $extension->getLogoutUrl();

        // Assert
        $this->assertSame('https://membership.example.com/logout', $result);
    }
}
