<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Authorization\MembershipManager as AuthorizationManager;
use App\Authorization\Provider\MembershipProvider;
use App\Twig\Extension\MembershipExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\MembershipExtension
 * @testdox 会員に関するTwig拡張機能
 */
class MembershipExtensionTest extends TestCase
{
    private function createAuthorizationManager(): AuthorizationManager
    {
        $provider = new MembershipProvider(
            'https://example.com',
            'client',
            'secret',
            ['openid'],
            'https://default.com/login',
            'https://default.com/logout'
        );

        return new AuthorizationManager($provider);
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extension = new MembershipExtension(
            'https://example.com/mypage',
            $this->createAuthorizationManager()
        );

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
    public function マイページのURLを返す(): void
    {
        // Arrange
        $extension = new MembershipExtension(
            'https://example.com/mypage',
            $this->createAuthorizationManager()
        );

        // Act
        $result = $extension->getMypageUrl();

        // Assert
        $this->assertSame('https://example.com/mypage', $result);
    }
}
