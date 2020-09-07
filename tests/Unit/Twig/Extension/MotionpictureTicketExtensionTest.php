<?php

/**
 * MotionpictureTicketExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\MotionpictureTicketExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * MotionpictureTicket extension test
 */
final class MotionpictureTicketExtensionTest extends TestCase
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
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class);
        $settings      = [];

        $extensionClassRef = new \ReflectionClass(MotionpictureTicketExtension::class);

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
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * test getTicketInquiryUrl
     *
     * @test
     * @return void
     */
    public function testGetTicketInquiryUrl()
    {
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
            ->makePartial();
        $settings      = [
            'ticket_url' => 'http://example.com',
        ];

        $extensionClassRef = new \ReflectionClass(MotionpictureTicketExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $theaterCode = '001';

        $result = $extensionMock->getTicketInquiryUrl($theaterCode);
        $this->assertStringContainsString($settings['ticket_url'], $result);
        $this->assertStringContainsString($theaterCode, $result);
    }

    /**
     * test getTicketEntranceUrl
     *
     * @test
     * @return void
     */
    public function testGetTicketEntranceUrl()
    {
        $extensionMock = Mockery::mock(MotionpictureTicketExtension::class)
            ->makePartial();
        $settings      = [
            'ticket_entrance_url' => 'http://example.com',
        ];

        $extensionClassRef = new \ReflectionClass(MotionpictureTicketExtension::class);

        $settingsPropertyRef = $extensionClassRef->getProperty('settings');
        $settingsPropertyRef->setAccessible(true);
        $settingsPropertyRef->setValue($extensionMock, $settings);

        $this->assertEquals(
            $settings['ticket_entrance_url'],
            $extensionMock->getTicketEntranceUrl()
        );
    }
}
