<?php

/**
 * ScheduleExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\ScheduleExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * Schedule extension test
 */
final class ScheduleExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $extensionMock = Mockery::mock(ScheduleExtension::class);
        $settings      = [];

        $extensionClassRef = new \ReflectionClass(ScheduleExtension::class);

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
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
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
     * test getApiUrl
     *
     * @test
     * @return void
     */
    public function testGetApiUrl()
    {
        $extensionMock = Mockery::mock(ScheduleExtension::class)
            ->makePartial();
        $settings      = [
            'api_url' => 'http://example.com',
        ];

        $extensionClassRef = new \ReflectionClass(ScheduleExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $this->assertEquals(
            $settings['api_url'],
            $extensionMock->getApiUrl()
        );
    }
}
