<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Authorization\Manager as AuthorizationManager;
use App\Authorization\Provider\CinemaSunshineRewardProvider;
use App\Authorization\SessionContainer;
use App\Session\SessionManager;
use App\Twig\Extension\UserExtension;
use App\User\Manager as UserManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\UserExtension
 * @testdox ユーザーに関するTwig拡張機能
 */
final class UserExtensionTest extends TestCase
{
    private function createAuthorizationManager(): AuthorizationManager
    {
        $provider = new CinemaSunshineRewardProvider(
            'example.com',
            'client',
            'secret',
            ['openid'],
            'https://default.com/login',
            'https://default.com/logout'
        );

        $sessionConfig  = new StandardConfig();
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setStorage(new ArrayStorage());
        $sessionContainer = new SessionContainer(
            $sessionManager->getContainer('auth')
        );

        return new AuthorizationManager($provider, $sessionContainer);
    }

    private function createUserManager(): UserManager
    {
        $sessionConfig  = new StandardConfig();
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setStorage(new ArrayStorage());

        return new UserManager($sessionManager->getContainer('user'));
    }

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extension = new UserExtension(
            $this->createUserManager(),
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
            ['login_url'],
            ['logout_url'],
            ['is_login'],
            ['login_user'],
        ];
    }
}
