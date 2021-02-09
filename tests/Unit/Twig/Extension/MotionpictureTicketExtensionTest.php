<?php

/**
 * MotionpictureTicketExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\MotionpictureTicketExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFunction;

/**
 * MotionpictureTicket extension test
 */
final class MotionpictureTicketExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class);
        $settings      = [];

        $extensionClassRef = new ReflectionClass(MotionpictureTicketExtension::class);

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
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
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
    public function testGetTicketInquiryUrl(): void
    {
        $settings = ['ticket_url' => 'http://example.com'];

        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
            ->makePartial();

        $extensionClassRef = new ReflectionClass(MotionpictureTicketExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $theaterCode = '001';

        $result = $extensionMock->getTicketInquiryUrl($theaterCode);
        $this->assertStringContainsString($settings['ticket_url'], $result);
        $this->assertStringContainsString($theaterCode, $result);
    }

    /**
     * @test
     */
    public function testGetTicketEntranceUrl(): void
    {
        $settings = ['ticket_entrance_url' => 'http://example.com'];

        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
            ->makePartial();

        $extensionClassRef = new ReflectionClass(MotionpictureTicketExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $this->assertEquals(
            $settings['ticket_entrance_url'],
            $extensionMock->getTicketEntranceUrl()
        );
    }
}
