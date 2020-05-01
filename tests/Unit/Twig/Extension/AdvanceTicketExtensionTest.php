<?php

/**
 * AdvanceTicketExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\AdvanceTicket;
use Cinemasunshine\Portal\Twig\Extension\AdvanceTicketExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * AdvanceTicket extension test
 */
final class AdvanceTicketExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
    {
        $extensionMock = Mockery::mock(AdvanceTicketExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * test getTypeLabel
     *
     * @test
     * @return void
     */
    public function testGetTypeLabel()
    {
        $extensionMock = Mockery::mock(AdvanceTicketExtension::class)
            ->makePartial();

        $this->assertEquals(
            'ムビチケカード',
            $extensionMock->getTypeLabel(AdvanceTicket::TYPE_MVTK)
        );

        $this->assertEquals(
            '紙券',
            $extensionMock->getTypeLabel(AdvanceTicket::TYPE_PAPER)
        );

        $this->assertNull($extensionMock->getTypeLabel(99));
    }
}
