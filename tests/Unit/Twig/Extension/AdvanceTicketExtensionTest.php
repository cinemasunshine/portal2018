<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\AdvanceTicket;
use App\Twig\Extension\AdvanceTicketExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\AdvanceTicketExtension
 * @testdox 前売り券に関するTwig拡張機能
 */
final class AdvanceTicketExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = new AdvanceTicketExtension();

        // Act
        $functions = $extensions->getFunctions();

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
            ['advance_ticket_type_label'],
        ];
    }

    /**
     * @covers ::getTypeLabel
     * @dataProvider typeLabelDataProvider
     * @test
     */
    public function testGetTypeLabel(int $input, ?string $expected): void
    {
        // Arrange
        $extensions = new AdvanceTicketExtension();

        // Act
        $result = $extensions->getTypeLabel($input);

        // Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return array<array{int, ?string}>
     */
    public function typeLabelDataProvider(): array
    {
        return [
            [AdvanceTicket::TYPE_MVTK, 'ムビチケカード'],
            [AdvanceTicket::TYPE_PAPER, '紙券'],
            [99, null],
        ];
    }
}
