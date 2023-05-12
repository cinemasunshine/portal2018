<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Authorization\Provider\RewardProvider;
use App\Authorization\RewardManager as AuthorizationManager;
use App\Authorization\SessionContainer;
use App\Session\SessionManager;
use App\Twig\Extension\RewardExtension;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @covers \App\Twig\Extension\RewardExtension
 * @testdox シネマサンシャインリワードに関するTwig拡張機能
 */
class RewardExtensionTest extends TestCase
{
    private function createAuthorizationManager(): AuthorizationManager
    {
        $provider = new RewardProvider(
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

    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extension = new RewardExtension($this->createAuthorizationManager());

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
            ['reward_login_url'],
            ['reward_logout_url'],
        ];
    }
}
