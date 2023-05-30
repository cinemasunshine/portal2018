<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\UserExtension;
use App\User\Provider\MembershipProvider;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\UserExtension
 * @testdox ユーザーに関するTwig拡張機能
 */
final class UserExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $userState = new MembershipProvider();
        $extension = new UserExtension($userState);

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
            ['is_login'],
            ['user_name'],
            ['is_reward_user'],
            ['is_membership_user'],
        ];
    }
}
