<?php

/**
 * ScheduleExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\ScheduleExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFunction;

/**
 * Schedule extension test
 */
final class ScheduleExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $extensionMock = Mockery::mock(ScheduleExtension::class);
        $settings      = [];

        $extensionClassRef = new ReflectionClass(ScheduleExtension::class);

        // execute constructor
        $constructorRef = $extensionClassRef->getConstructor();
        $constructorRef->invoke($extensionMock, $settings);

        // test property "settings"
        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $this->assertEquals(
            $settings,
            $settingsPropertyRef->getValue($extensionMock)
        );
    }

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $extensionMock = Mockery::mock(ScheduleExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @test
     */
    public function testGetApiUrl(): void
    {
        $settings = ['api_url' => 'http://example.com'];

        $extensionMock = Mockery::mock(ScheduleExtension::class)
            ->makePartial();

        $extensionClassRef = new ReflectionClass(ScheduleExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $this->assertEquals(
            $settings['api_url'],
            $extensionMock->getApiUrl()
        );
    }
}
